<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\ShiftController;
use App\Http\Controllers\Gudang\StockInController;
use App\Http\Controllers\Gudang\MutationController;
use App\Http\Controllers\Supervisor\DashboardController; // <- Controller baru kita
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ===== KASIR =====
    Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/pos',                    \App\Livewire\Kasir\PosSystem::class)->name('pos');
        Route::get('/shift/open',             [ShiftController::class, 'open'])->name('shift.open');
        Route::post('/shift/open',            [ShiftController::class, 'storeOpen'])->name('shift.open.store');
        Route::get('/shift/close',            [ShiftController::class, 'close'])->name('shift.close');
        Route::post('/shift/close',           [ShiftController::class, 'storeClose'])->name('shift.close.store');
        Route::get('/shift/report',           [ShiftController::class, 'report'])->name('shift.report');
        Route::get('/transaction/{id}/struk', [PosController::class, 'struk'])->name('struk');
    });

    // ===== PEGAWAI GUDANG =====
    Route::middleware('role:pegawai_gudang')->prefix('gudang')->name('gudang.')->group(function () {
        Route::get('/',          [StockInController::class, 'index'])->name('dashboard');
        Route::get('/stock-in',  [StockInController::class, 'create'])->name('stock.in');
        Route::post('/stock-in', [StockInController::class, 'store'])->name('stock.in.store');
        Route::get('/mutasi',    [MutationController::class, 'index'])->name('mutation');
        Route::post('/mutasi',   [MutationController::class, 'store'])->name('mutation.store');
        Route::get('/notifikasi', [StockInController::class, 'notifications'])->name('notif');
    });

    // ===== SUPERVISOR =====
    Route::middleware(['auth'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::view('/monitoring', 'supervisor.monitoring')->name('monitoring');
        Route::view('/void', 'supervisor.void')->name('void');
        Route::view('/opname', 'supervisor.opname')->name('opname');
        Route::view('/audit', 'supervisor.audit')->name('audit');
        Route::redirect('/', '/supervisor/monitoring');
    });
});
