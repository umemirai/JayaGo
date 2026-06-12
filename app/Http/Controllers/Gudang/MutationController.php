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
                                  ->paginate(20);

        return view('gudang.mutation', compact('products', 'mutations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:display,in,adjustment',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($validated) {
            $product   = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $qtyBefore = $product->stock;

            if ($validated['type'] === 'display') {
                // Mutasi display: dari gudang ke rak — stok sama, hanya dicatat
                // Jika sistemnya memisahkan stok gudang & display, adjust di sini
                $change = 0;
                $notes  = "[DISPLAY] " . $validated['notes'];
            } elseif ($validated['type'] === 'adjustment') {
                $change = $validated['quantity'];
                $notes  = "[ADJUSTMENT] " . $validated['notes'];
                $product->increment('stock', $change);
            } else {
                $change = $validated['quantity'];
                $notes  = $validated['notes'];
                $product->increment('stock', $change);
            }

            StockMutation::create([
                'product_id'      => $product->id,
                'user_id'         => Auth::id(),
                'type'            => $validated['type'],
                'quantity_before' => $qtyBefore,
                'quantity_change' => $change,
                'quantity_after'  => $product->fresh()->stock,
                'notes'           => $notes,
                'mutation_date'   => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Mutasi barang berhasil dicatat!');
    }
}