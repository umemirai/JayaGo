<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $user = auth()->user();
$role = $user->role ?? ($user->hasRole('kasir') ? 'kasir' : ($user->hasRole('pegawai_gudang') ? 'pegawai_gudang' : 'default'));

return match($role) {
    'manajer'        => redirect('/manajer/dashboard'),
    'kasir'          => redirect('/kasir/pos'),
    'pegawai_gudang' => redirect('/gudang'),
    'supervisor'     => redirect('/kasir/pos'),
    default          => redirect('/login'),
};
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}