<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        $totalTransaksi = Transaction::where('branch_id', $branchId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $totalPendapatan = Transaction::where('branch_id', $branchId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total');

        $stokMenipis = Product::where('branch_id', $branchId)
            ->whereColumn('stock', '<=', 'stock_minimum')
            ->count();

        $jumlahKaryawan = User::where('branch_id', $branchId)
            ->whereIn('role', ['supervisor', 'kasir', 'pegawai_gudang'])
            ->count();

        $transaksiHarian = Transaction::where('branch_id', $branchId)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah, SUM(total) as pendapatan')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('manajer.dashboard', compact(
            'totalTransaksi',
            'totalPendapatan',
            'stokMenipis',
            'jumlahKaryawan',
            'transaksiHarian'
        ));
    }
}