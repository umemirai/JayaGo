<?php
namespace App\Livewire\Gudang;

use Livewire\Component;
use Livewire\Attributes\Computed;
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
    public string $searchProduct = '';

    public function mount(): void
    {
        $this->loadProducts();
    }

    public function loadProducts(): void
    {
        $products = Product::orderBy('name')->get();

        $this->items = $products->map(fn($product) => [
            'product_id'     => $product->id,
            'name'           => $product->name,
            'barcode'        => $product->barcode,
            'unit'           => $product->unit,
            'system_stock'   => $product->stock,
            'physical_stock' => $product->stock,
            'difference'     => 0,
            'notes'          => '',
        ])->toArray();

        $this->isLoaded = true;
    }

    #[Computed]
    public function filteredItems()
    {
        if (empty($this->searchProduct)) {
            return collect($this->items)->keys()->toArray();
        }

        return collect($this->items)
            ->filter(fn($item) =>
                str_contains(strtolower($item['name']), strtolower($this->searchProduct)) ||
                str_contains($item['barcode'], $this->searchProduct)
            )
            ->keys()
            ->toArray();
    }

    public function updatePhysical(int $index, $value): void
    {
        $value = is_numeric($value) ? (int) $value : 0;

        $this->items[$index]['physical_stock'] = $value;
        $this->items[$index]['difference']     = $value - $this->items[$index]['system_stock'];
    }

    #[Computed]
    public function totalDifference(): int
    {
        return collect($this->items)->sum('difference');
    }

    #[Computed]
    public function itemsWithDifference(): int
    {
        return collect($this->items)->where('difference', '!=', 0)->count();
    }

    public function submit(string $status = 'submitted'): void
    {
        $this->validate([
            'items'                  => 'required|array|min:1',
            'items.*.physical_stock' => 'required|integer|min:0',
        ], [
            'items.*.physical_stock.required' => 'Stok fisik wajib diisi untuk semua produk.',
            'items.*.physical_stock.min'      => 'Stok fisik tidak boleh negatif.',
        ]);

        DB::transaction(function () use ($status) {
            $opname = StockOpname::create([
                'user_id'     => Auth::id(),
                'opname_code' => 'OPN-' . now()->format('Ymd-His'),
                'opname_date' => today(),
                'status'      => $status,
                'notes'       => $this->notes,
            ]);

            foreach ($this->items as $item) {
                StockOpnameItem::create([
                    'stock_opname_id' => $opname->id,
                    'product_id'      => $item['product_id'],
                    'system_stock'    => $item['system_stock'],
                    'physical_stock'  => $item['physical_stock'],
                    'difference'      => $item['difference'],
                    'notes'           => $item['notes'] ?? null,
                ]);

                if ($status === 'submitted' && $item['difference'] !== 0) {
                    $product = Product::lockForUpdate()->find($item['product_id']);
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
                        'notes'           => 'Penyesuaian dari Stock Opname',
                        'mutation_date'   => now(),
                    ]);
                }
            }

            if ($status === 'submitted') {
                $opname->update(['status' => 'approved']);
            }
        });

        $this->dispatch('notify',
            type: 'success',
            message: $status === 'submitted'
                ? 'Stock opname selesai! Stok sistem telah diperbarui sesuai hasil hitung fisik.'
                : 'Draft stock opname berhasil disimpan.'
        );

        $this->loadProducts();
        $this->notes = '';
    }

    public function render()
    {
        return view('livewire.gudang.stock-opname-form')
               ->layout('layouts.gudang');
    }
}