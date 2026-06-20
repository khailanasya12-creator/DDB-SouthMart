<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController; // Memastikan rute tahu di mana letak file PosController

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

// Route baru untuk menangani submit form tambah data barang
Route::post('/tebet/pos/store-product', [PosController::class, 'storeProduct'])->name('tebet.pos.store-product');
Route::get('/tebet/produk', [\App\Http\Controllers\PosController::class, 'indexProduk'])->name('tebet.produk.index');
Route::get('/tebet/monitoring', [\App\Http\Controllers\PosController::class, 'indexMonitoring'])->name('tebet.monitoring.index');
Route::get('/tebet/replikasi', [\App\Http\Controllers\PosController::class, 'indexReplikasis'])->name('tebet.replikasi.index');
Route::post('/tebet/replikasi/sync', [\App\Http\Controllers\PosController::class, 'prosesSync'])->name('tebet.replikasi.sync');
Route::get('/tebet/penjualan-nasional', [\App\Http\Controllers\PosController::class, 'indexPenjualanNasional'])->name('tebet.penjualan.nasional');
Route::get('/tebet/query-lintas-node', [\App\Http\Controllers\PosController::class, 'indexQueryLintasNode'])->name('tebet.query.lintasnode');
Route::get('/tebet/inventaris', [\App\Http\Controllers\PosController::class, 'indexInventaris'])->name('tebet.inventaris.index');
Route::get('/tebet/laporan', [\App\Http\Controllers\PosController::class, 'indexLaporan'])->name('tebet.laporan.index');
Route::get('/tebet/cabang', [\App\Http\Controllers\PosController::class, 'indexCabang'])->name('tebet.cabang.index');
Route::get('/tebet/pengguna', [\App\Http\Controllers\PosController::class, 'indexPengguna'])->name('tebet.pengguna.index');
Route::get('/tebet/pengaturan', [\App\Http\Controllers\PosController::class, 'indexPengaturan'])->name('tebet.pengaturan.index');
Route::delete('/tebet/produk/{id}', [\App\Http\Controllers\PosController::class, 'destroyProduk'])->name('tebet.produk.destroy');