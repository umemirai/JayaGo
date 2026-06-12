<?php
namespace App\Livewire\Gudang;

use Livewire\Component;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\StockMutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOpnameForm extends Component
{
    public array $items = [];
    public string $notes = '';
    public bool $isLoaded = false;

    public function loadProducts(): void
    {
        $products = Product::orderBy('name')->get();

        $this->items = $products->map(fn($product) => [
            'product_id'    => $product->id,
            'name'          => $product->name,
            'barcode'       => $product->barcode,
            'system_stock'  => $product->stock,
            'physical_stock' => $product->stock, // default sama dulu
            'difference'    => 0,
            'notes'         => '',
        ])->toArray();

        $this->isLoaded = true;
    }

    public function updatePhysical(int $index, int $value): void
    {
        $this->items[$index]['physical_stock'] = $value;
        $this->items[$index]['difference']     = $value - $this->items[$index]['system_stock'];
    }

    public function submit(string $status = 'draft'): void
    {
        $this->validate([
            'items'                   => 'required|array|min:1',
            'items.*.physical_stock'  => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($status) {
            $opname = StockOpname::create([
                'user_id'      => Auth::id(),
                'opname_code'  => 'OPN-' . date('YmdHis'),
                'opname_date'  => today(),
                'status'       => $status,
                'notes'        => $this->notes,
            ]);

            foreach ($this->items as $item) {
                StockOpnameItem::create([
                    'stock_opname_id' => $opname->id,
                    'product_id'      => $item['product_id'],
                    'system_stock'    => $item['system_stock'],
                    'physical_stock'  => $item['physical_stock'],
                    'difference'      => $item['difference'],
                    'notes'           => $item['notes'],
                ]);

                // Jika disubmit (bukan draft), update stok
                if ($status === 'submitted' && $item['difference'] !== 0) {
                    $product = Product::find($item['product_id']);
                    $before  = $product->stock;
                    $product->update(['stock' => $item['physical_stock']]);

                    StockMutation::create([
                        'product_id'      => $item['product_id'],
                        'user_id'         => Auth::id(),
                        'type'            => 'opname',
                        'quantity_before' => $before,
                        'quantity_change' => $item['difference'],
                        'quantity_after'  => $item['physical_stock'],
                        'reference'       => $opname->opname_code,
                        'notes'           => 'Stock Opname',
                        'mutation_date'   => now(),
                    ]);
                }
            }
        });

        $this->dispatch('notify', type: 'success',
            message: $status === 'submitted' ? 'Stock opname selesai & stok diperbarui!' : 'Draft disimpan!');

        $this->reset(['items', 'notes', 'isLoaded']);
    }

    public function render()
    {
        return view('livewire.gudang.stock-opname-form')
               ->layout('layouts.gudang');
    }
}