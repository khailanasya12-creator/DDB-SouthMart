<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Redirect root
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::any('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route (Auth Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== DAFTAR RUTE TAMBALAN (BIAR LAYOUT TEMANMU TIDAK ERROR) ====================
    // Kita buatkan rute palsu berawalan 'admin.' sesuai yang diminta oleh file admin.blade.php milik temanmu
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/monitoring', [DashboardController::class, 'index'])->name('admin.monitoring');
    Route::get('/admin/national-sales', [DashboardController::class, 'index'])->name('admin.national-sales');
    Route::get('/admin/cross-node-query', [DashboardController::class, 'index'])->name('admin.cross-node-query');
    Route::get('/admin/produk', [DashboardController::class, 'index'])->name('admin.produk.index');
    Route::get('/admin/inventory', [DashboardController::class, 'index'])->name('admin.inventory');
    Route::get('/admin/branches', [DashboardController::class, 'index'])->name('admin.branches');
    Route::get('/admin/replication', [DashboardController::class, 'index'])->name('admin.replication');
    Route::get('/admin/reports', [DashboardController::class, 'index'])->name('admin.reports');
    Route::get('/admin/users', [DashboardController::class, 'index'])->name('admin.users');
    Route::get('/admin/settings', [DashboardController::class, 'index'])->name('admin.settings');
    Route::get('/admin/doc', [DashboardController::class, 'index'])->name('doc.index');
    // ================================================================================================

    // ==================== RUTE BARU KASIR TEBET MASUK KE SINI ====================
    // Rute utama halaman POS Kasir Tebet milikmu
    Route::get('/tebet/pos', [App\Http\Controllers\PosController::class, 'index'])->name('tebet.pos.index'); 

    // Rute untuk memproses transaksi checkout kasir Tebet
    Route::post('/tebet/pos/checkout', [App\Http\Controllers\PosController::class, 'checkout'])->name('tebet.pos.checkout');
    // =============================================================================
});