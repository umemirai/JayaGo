<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\ShiftController;
use App\Http\Controllers\Gudang\StockInController;
use App\Http\Controllers\Gudang\MutationController;
use App\Http\Controllers\Supervisor\DashboardController;
use App\Http\Controllers\Owner\OwnerController;
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
        Route::get('/pos', \App\Livewire\Kasir\PosSystem::class)->name('pos');
        Route::get('/shift/open', [ShiftController::class, 'open'])->name('shift.open');
        Route::post('/shift/open', [ShiftController::class, 'storeOpen'])->name('shift.open.store');
        Route::get('/shift/close', [ShiftController::class, 'close'])->name('shift.close');
        Route::post('/shift/close', [ShiftController::class, 'storeClose'])->name('shift.close.store');
        Route::get('/shift/report', [ShiftController::class, 'report'])->name('shift.report');
        Route::get('/transaction/{id}/struk', [PosController::class, 'struk'])->name('struk');
    });

    // ===== PEGAWAI GUDANG =====
    Route::middleware('role:pegawai_gudang')->prefix('gudang')->name('gudang.')->group(function () {
        Route::get('/', [StockInController::class, 'index'])->name('dashboard');
        Route::get('/stock-in', [StockInController::class, 'create'])->name('stock.in');
        Route::post('/stock-in', [StockInController::class, 'store'])->name('stock.in.store');
        Route::get('/mutasi', [MutationController::class, 'index'])->name('mutation');
        Route::post('/mutasi', [MutationController::class, 'store'])->name('mutation.store');
        Route::get('/notifikasi', [StockInController::class, 'notifications'])->name('notif');
    });

    // ===== SUPERVISOR =====
    Route::middleware('role:supervisor')->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::view('/monitoring', 'supervisor.monitoring')->name('monitoring');
        Route::view('/void', 'supervisor.void')->name('void');
        Route::view('/opname', 'supervisor.opname')->name('opname');
        Route::view('/audit', 'supervisor.audit')->name('audit');
        Route::redirect('/', '/supervisor/monitoring');
    });

    // ===== MANAJER =====
    Route::middleware('cek.role:manajer')->prefix('manajer')->name('manajer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Manajer\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan/transaksi', [\App\Http\Controllers\Manajer\LaporanController::class, 'transaksi'])->name('laporan.transaksi');
        Route::get('/laporan/transaksi/export', [\App\Http\Controllers\Manajer\LaporanController::class, 'exportTransaksi'])->name('laporan.transaksi.export');
        Route::get('/laporan/stok', [\App\Http\Controllers\Manajer\LaporanController::class, 'stok'])->name('laporan.stok');
        Route::get('/laporan/stok/export', [\App\Http\Controllers\Manajer\LaporanController::class, 'exportStok'])->name('laporan.stok.export');
        Route::get('/sdm', [\App\Http\Controllers\Manajer\SdmController::class, 'index'])->name('sdm.index');
        Route::get('/sdm/create', [\App\Http\Controllers\Manajer\SdmController::class, 'create'])->name('sdm.create');
        Route::post('/sdm', [\App\Http\Controllers\Manajer\SdmController::class, 'store'])->name('sdm.store');
        Route::get('/sdm/{user}/edit', [\App\Http\Controllers\Manajer\SdmController::class, 'edit'])->name('sdm.edit');
        Route::put('/sdm/{user}', [\App\Http\Controllers\Manajer\SdmController::class, 'update'])->name('sdm.update');
        Route::delete('/sdm/{user}', [\App\Http\Controllers\Manajer\SdmController::class, 'destroy'])->name('sdm.destroy');
    });

    Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {

        // 1. Global Dashboard
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');

        // 2. CRUD Manajemen Cabang
        Route::get('/branches', [OwnerController::class, 'branches'])->name('branches');
        Route::post('/branches/store', [OwnerController::class, 'storeBranch'])->name('branches.store');
        Route::post('/branches/update', [OwnerController::class, 'updateBranch'])->name('branches.update');
        Route::get('/branches/delete/{id}', [OwnerController::class, 'deleteBranch'])->name('branches.delete');

        // 3. CRUD Manajemen User & Penempatan Cabang
        Route::get('/users', [OwnerController::class, 'users'])->name('users');

        // JALUR SAKTI YANG DICARI BLADE: Kombinasi prefix name 'owner.' + 'users.updateBranch'
        Route::post('/users/update-branch', [OwnerController::class, 'updateUserBranch'])->name('users.updateBranch');

        // 4. Menu Lainnya
        Route::get('/reports', [OwnerController::class, 'reports'])->name('reports');
        Route::get('/audit-log', [OwnerController::class, 'auditLog'])->name('audit');
    });
});
