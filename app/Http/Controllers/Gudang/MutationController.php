<?php
namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutationController extends Controller
{
    public function index()
    {
        $products  = Product::orderBy('name')->get();

        $mutations = StockMutation::with(['product', 'user'])
                                  ->orderByDesc('mutation_date')
                                  ->paginate(15);

        return view('gudang.mutation', compact('products', 'mutations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:display,adjustment_in,adjustment_out',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'required|string|max:500',
        ], [
            'product_id.required' => 'Pilih produk terlebih dahulu.',
            'type.required'       => 'Pilih jenis mutasi.',
            'quantity.required'   => 'Jumlah wajib diisi.',
            'quantity.min'        => 'Jumlah minimal 1.',
            'notes.required'      => 'Catatan/alasan mutasi wajib diisi.',
        ]);

        DB::transaction(function () use ($validated) {
            $product   = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $qtyBefore = $product->stock;

            switch ($validated['type']) {
                case 'display':
                    // Perpindahan dari gudang ke rak — stok total tidak berubah,
                    // hanya dicatat sebagai jejak audit perpindahan barang
                    $change = 0;
                    $label  = 'Perpindahan ke rak display';
                    break;

                case 'adjustment_in':
                    // Penyesuaian stok bertambah (misal: ditemukan barang tercecer)
                    $change = $validated['quantity'];
                    $product->increment('stock', $change);
                    $label  = 'Penyesuaian stok (+)';
                    break;

                case 'adjustment_out':
                    // Penyesuaian stok berkurang (misal: rusak/hilang)
                    $change = -$validated['quantity'];
                    if ($validated['quantity'] > $product->stock) {
                        throw new \Exception('Jumlah pengurangan melebihi stok tersedia.');
                    }
                    $product->decrement('stock', $validated['quantity']);
                    $label  = 'Penyesuaian stok (-)';
                    break;
            }

            StockMutation::create([
                'product_id'      => $product->id,
                'user_id'         => Auth::id(),
                'type'            => $validated['type'] === 'display' ? 'display' : 'out',
                'quantity_before' => $qtyBefore,
                'quantity_change' => $validated['type'] === 'display' ? $validated['quantity'] : $change,
                'quantity_after'  => $product->fresh()->stock,
                'notes'           => "[{$label}] " . $validated['notes'],
                'mutation_date'   => now(),
            ]);
        });

        return redirect()->route('gudang.mutation')
                          ->with('success', 'Mutasi barang berhasil dicatat!');
    }
}