<?php
namespace App\Livewire\Kasir;

use Livewire\Component;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMutation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosSystem extends Component
{
    public string $searchQuery = '';
    public array $cart = [];
    public $paid = 0;
    public $discount = 0;
    public string $paymentMethod = 'cash';
    public ?int $lastTransactionId = null;

    public function updatedPaid($value): void
    {
        $this->paid = is_numeric($value) ? (float) $value : 0;
    }

    public function updatedDiscount($value): void
    {
        $this->discount = is_numeric($value) ? (float) $value : 0;
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function getTotalProperty(): float
    {
        return max(0, $this->getSubtotalProperty() - (float) $this->discount);
    }

    public function getChangeProperty(): float
    {
        return max(0, (float) $this->paid - $this->getTotalProperty());
    }

    public function getSearchResultsProperty()
    {
        if (strlen($this->searchQuery) < 2) return collect();

        return Product::where(function($q) {
            $q->where('name', 'like', "%{$this->searchQuery}%")
              ->orWhere('barcode', 'like', "%{$this->searchQuery}%");
        })->where('stock', '>', 0)->limit(8)->get();
    }

    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);

        if ($product->stock <= 0) {
            $this->dispatch('notify', type: 'error', message: "Stok {$product->name} habis!");
            return;
        }

        $existingQty = $this->cart[$productId]['qty'] ?? 0;
        if ($existingQty >= $product->stock) {
            $this->dispatch('notify', type: 'warning', message: "Stok {$product->name} tidak mencukupi!");
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['qty'] * $product->price;
        } else {
            $this->cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'barcode'  => $product->barcode,
                'price'    => (float) $product->price,
                'qty'      => 1,
                'subtotal' => (float) $product->price,
                'stock'    => $product->stock,
            ];
        }

        $this->searchQuery = '';
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    public function updateQty(int $productId, int $qty): void
    {
        if ($qty <= 0) {
            $this->removeFromCart($productId);
            return;
        }

        $product = Product::find($productId);
        if ($qty > $product->stock) {
            $this->dispatch('notify', type: 'warning', message: 'Melebihi stok tersedia!');
            return;
        }

        $this->cart[$productId]['qty']     = $qty;
        $this->cart[$productId]['subtotal'] = $qty * $this->cart[$productId]['price'];
    }

    public function processTransaction(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', type: 'error', message: 'Keranjang kosong!');
            return;
        }

        if ($this->paymentMethod === 'cash' && (float) $this->paid < $this->getTotalProperty()) {
            $this->dispatch('notify', type: 'error', message: 'Pembayaran kurang!');
            return;
        }

        DB::transaction(function () {
            $transaction = Transaction::create([
                'invoice_number'   => Transaction::generateInvoice(),
                'user_id'          => Auth::id(),
                'subtotal'         => $this->getSubtotalProperty(),
                'discount'         => (float) $this->discount,
                'total'            => $this->getTotalProperty(),
                'paid'             => (float) $this->paid,
                'change'           => $this->getChangeProperty(),
                'payment_method'   => $this->paymentMethod,
                'status'           => 'completed',
                'transaction_date' => now(),
            ]);

            foreach ($this->cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'product_name'   => $item['name'],
                    'price'          => $item['price'],
                    'quantity'       => $item['qty'],
                    'subtotal'       => $item['subtotal'],
                ]);

                $product   = Product::lockForUpdate()->find($item['id']);
                $qtyBefore = $product->stock;
                $product->decrement('stock', $item['qty']);

                StockMutation::create([
                    'product_id'      => $item['id'],
                    'user_id'         => Auth::id(),
                    'type'            => 'out',
                    'quantity_before' => $qtyBefore,
                    'quantity_change' => -$item['qty'],
                    'quantity_after'  => $qtyBefore - $item['qty'],
                    'reference'       => $transaction->invoice_number,
                    'notes'           => 'Penjualan POS',
                    'mutation_date'   => now(),
                ]);

                $product->refresh();
                if ($product->isLowStock()) {
                    \App\Jobs\SendLowStockNotification::dispatch($product);
                }
            }

            $this->lastTransactionId = $transaction->id;
        });

        $this->cart     = [];
        $this->paid     = 0;
        $this->discount = 0;

        $this->dispatch('notify', type: 'success', message: 'Transaksi berhasil!');
        $this->dispatch('transaction-success', transactionId: $this->lastTransactionId);
    }

    public function clearCart(): void
    {
        $this->cart     = [];
        $this->paid     = 0;
        $this->discount = 0;
    }

    public function render()
    {
        return view('livewire.kasir.pos-system')
               ->layout('layouts.kasir');
    }
}