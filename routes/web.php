<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LaporanController;

// Redirect ke halaman login saat mengakses root URL
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard untuk setiap role
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/petugas/dashboard', function () {
        return 'Selamat datang di dashboard Petugas';
    })->name('petugas.dashboard');

    Route::get('/kasir/dashboard', function () {
        return 'Selamat datang di dashboard Kasir';
    })->name('kasir.dashboard');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('petugas', PetugasController::class)->except(['show']);

    Route::resource('pelanggan', PelangganController::class);

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
});
