<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        // 1. Kita siapkan array data dummy omzet untuk 5 cabang JayMart
        $dataOmzet = [
            'labels' => ['Cabang Jakarta', 'Cabang Bandung', 'Cabang Surabaya', 'Cabang Medan', 'Cabang Makassar'],
            'values' => [15000000, 12000000, 18000000, 9500000, 14000000]
        ];

        return view('owner.dashboard', compact('dataOmzet'));
    }

    // ==========================================
    // CRUDS MANAJEMEN CABANG (ELOQUENT ORM)
    // ==========================================

    // 1. TAMPILKAN DATA DARI DATABASE
    public function branches()
    {
        // Urutkan dari ID terkecil ke terbesar agar rapi di Blade
        $branches = Branch::orderBy('id', 'asc')->get();
        return view('owner.branches', compact('branches'));
    }

    // FUNGSI SIMPAN
    public function storeBranch(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        // 1. CARI ID YANG KOSONG (REUSE ID)
        $nextId = 1;

        // Looping terus sampai kita menemukan ID yang benar-benar belum terdaftar di database
        while (Branch::where('id', $nextId)->exists()) {
            $nextId++;
        }

        // 2. SIMPAN KE DATABASE DENGAN ID YANG SUDAH DITEMUKAN
        Branch::create([
            'id' => $nextId, // Kita paksa isi ID-nya pakai angka kosong tadi
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'is_active' => 1
        ]);

        return redirect()->route('owner.branches')->with('success', 'Cabang baru berhasil disimpan di ID #' . $nextId . '! 🎉');
    }

    // FUNGSI UPDATE
    public function updateBranch(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        $branch = Branch::findOrFail($request->id);
        $branch->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('owner.branches')->with('success', 'Data cabang berhasil diperbarui!');
    }

    // 4. FUNGSI HAPUS DATA (DELETE)
    public function deleteBranch(int $id)
    {
        // Cari data berdasarkan ID di rute, lalu eksekusi hapus
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->back()->with('success', 'Cabang berhasil dihapus dari database pusat! 🗑️');
    }

    // Pastikan ini ada di dalam class OwnerController

    public function users()
    {
        $users = User::with(['branch', 'roles'])->get();
        $branches = Branch::all();
        return view('owner.users', compact('users', 'branches'));
    }

    // === TAMBAHKAN FUNGSI SAKTI INI DI BAWAH FUNGSY USERS ===
    public function updateUserBranch(Request $request)
    {
        // Validasi data input form modal dari Blade
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'branch_id' => 'nullable|exists:branches,id', // nullable jika ingin dikosongkan/bebas tugas
        ]);

        // Cari user berdasarkan ID, lalu update kolom branch_id-nya
        $user = User::findOrFail($request->user_id);
        $user->update([
            'branch_id' => $request->branch_id
        ]);

        // Kembalikan ke halaman sebelumnya dengan alert sukses
        return redirect()->back()->with('success', 'Penempatan cabang untuk ' . $user->name . ' berhasil diperbarui! 👔');
    }

    public function reports(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Tarik data dengan relasi kasir (cashier) dan cabang (branch)
        $transactions = Transaction::with(['cashier', 'branch'])
            ->filterByDate($startDate, $endDate)
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Menggunakan kolom 'total' sesuai dengan isi fillable model temanmu
        $totalOmzet = $transactions->sum('total');

        return view('owner.reports', compact('transactions', 'startDate', 'endDate', 'totalOmzet'));
    }

    public function auditLog()
    {
        $logs = \DB::table('activity_logs')->latest()->get();

        return view('owner.audit', compact('logs'));
    }
}
