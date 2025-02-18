<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LaporanBarangTerjualController;
use App\Http\Controllers\Admin\KategoriBarangController;
use App\Http\Controllers\Admin\ItemBarangController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Kasir\PembelianController;
use App\Http\Controllers\Kasir\MemberController;
use App\Http\Controllers\Kasir\RiwayatController;
use App\Http\Controllers\Kasir\BarangController;
use App\Models\ActivityLog;

// Redirect ke halaman login saat mengakses root URL
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.process');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk admin
Route::middleware(['auth', 'role:administrator', 'prevent-back-history'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('petugas', PetugasController::class);
    Route::get('pelanggan/{id}', [PelangganController::class, 'restore'])->name('pelanggan.restore');
    Route::resource('pelanggan', PelangganController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::get('laporan/nota/{kode_transaksi}', [LaporanController::class, 'nota'])->name('laporan.nota');
    Route::resource('kategori', KategoriBarangController::class);
    Route::get('barang/laporan', [ItemBarangController::class, 'cetakLaporan'])->name('barang.laporan');
    Route::resource('barang', ItemBarangController::class);
    Route::resource('logs', ActivityLogController::class);
    Route::resource('terjual', LaporanBarangTerjualController::class);
    Route::get('transaksi', [TransaksiController::class, 'create'])->name('transaksi.index');
    Route::post('transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/nota/{kode_transaksi}', [TransaksiController::class, 'nota'])->name('transaksi.nota');
    Route::resource('stok', StokController::class);
});

// Route untuk kasir
Route::middleware(['auth', 'role:kasir', 'prevent-back-history'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
    Route::get('pembelian', [PembelianController::class, 'create'])->name('pembelian.index');
    Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/nota/{kode_transaksi}', [PembelianController::class, 'nota'])->name('pembelian.nota');
    Route::resource('member', MemberController::class);
    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('riwayat/nota/{kode_transaksi}', [RiwayatController::class, 'nota'])->name('riwayat.nota');
    Route::get('riwayat/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');
    Route::resource('barang', BarangController::class);
});