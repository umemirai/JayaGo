<?php
namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $lowStockProducts = Product::where('stock', '<=', DB::raw('stock_minimum'))
                                   ->orderBy('stock')
                                   ->get();

        $recentMutations = StockMutation::with(['product', 'user'])
                                        ->latest()
                                        ->limit(10)
                                        ->get();

        return view('gudang.dashboard', compact('lowStockProducts', 'recentMutations'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('gudang.stock-in', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'quantity'     => 'required|integer|min:1|max:9999',
            'reference'    => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:500',
            'supplier'     => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($validated) {
            $product   = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $qtyBefore = $product->stock;

            $product->increment('stock', $validated['quantity']);

            StockMutation::create([
                'product_id'      => $product->id,
                'user_id'         => Auth::id(),
                'type'            => 'in',
                'quantity_before' => $qtyBefore,
                'quantity_change' => $validated['quantity'],
                'quantity_after'  => $qtyBefore + $validated['quantity'],
                'reference'       => $validated['reference'] ?? null,
                'notes'           => $validated['notes'] ?? null,
                'mutation_date'   => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
    }

    public function notifications()
    {
        $lowStocks = Product::where('stock', '<=', DB::raw('stock_minimum'))
                            ->orderBy('stock')
                            ->paginate(20);

        return view('gudang.notifications', compact('lowStocks'));
    }
}