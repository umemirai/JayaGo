<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SdmController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $karyawan = User::where('branch_id', $branchId)
            ->whereIn('role', ['supervisor', 'kasir', 'pegawai_gudang'])
            ->get();

        return view('manajer.sdm.index', compact('karyawan'));
    }

    public function create()
    {
        return view('manajer.sdm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:supervisor,kasir,pegawai_gudang',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'branch_id' => Auth::user()->branch_id,
        ]);

        return redirect()->route('manajer.sdm.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        // Pastikan hanya bisa edit karyawan di cabang sendiri
        if ($user->branch_id !== Auth::user()->branch_id) {
            abort(403);
        }

        return view('manajer.sdm.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->branch_id !== Auth::user()->branch_id) {
            abort(403);
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:supervisor,kasir,pegawai_gudang',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('manajer.sdm.index')->with('success', 'Data karyawan berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->branch_id !== Auth::user()->branch_id) {
            abort(403);
        }

        $user->delete();
        return redirect()->route('manajer.sdm.index')->with('success', 'Karyawan berhasil dihapus!');
    }
}