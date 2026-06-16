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

            // --- TAMBAHKAN BLOK INI UNTUK OWNER ---
            if ($user->hasRole('owner')) {
                return redirect()->route('owner.dashboard');
            }
            // --------------------------------------

            if ($user->hasRole('manajer')) {
                return redirect('/manajer/dashboard');
            }

            if ($user->hasRole('kasir')) {
                return redirect('/kasir/pos');
            }

            if ($user->hasRole('pegawai_gudang')) {
                return redirect('/gudang');
            }

            if ($user->hasRole('supervisor')) {
                return redirect()->intended(route('supervisor.monitoring'));
            }

            return redirect('/');
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
