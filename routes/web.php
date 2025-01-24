<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    Route::get('/admin/dashboard', function () {
        return 'Selamat datang di dashboard Administrator';
    })->name('admin.dashboard');

    Route::get('/petugas/dashboard', function () {
        return 'Selamat datang di dashboard Petugas';
    })->name('petugas.dashboard');

    Route::get('/kasir/dashboard', function () {
        return 'Selamat datang di dashboard Kasir';
    })->name('kasir.dashboard');
});


