<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class LaporanController extends Controller
{
    public function transaksi()
    {
        $branchId = Auth::user()->branch_id;
        $transaksi = Transaction::with('user')
            ->where('branch_id', $branchId)
            ->latest()
            ->paginate(20);

        return view('manajer.laporan.transaksi', compact('transaksi'));
    }

    public function exportTransaksi()
    {
        $branchId = Auth::user()->branch_id;
        $namaCabang = Auth::user()->branch->name;
        return Excel::download(
            new TransaksiExport($branchId),
            'laporan-transaksi-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function stok()
    {
        $branchId = Auth::user()->branch_id;
        $produk = Product::where('branch_id', $branchId)
            ->orderBy('name')
            ->get();

        return view('manajer.laporan.stok', compact('produk'));
    }

    public function exportStok()
    {
        $branchId = Auth::user()->branch_id;
        $namaCabang = Auth::user()->branch->name;
        $produk = Product::where('branch_id', $branchId)->orderBy('name')->get();

        $pdf = Pdf::loadView('manajer.laporan.stok-pdf', compact('produk', 'namaCabang'));
        return $pdf->download('laporan-stok-' . now()->format('Y-m-d') . '.pdf');
    }
}