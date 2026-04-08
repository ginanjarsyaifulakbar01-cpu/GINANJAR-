<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\UserBackendController;
use App\Http\Controllers\backend\BukuController;
use App\Http\Controllers\backend\CategoryController;

// ROUTE LOGIN
Route::get('/login', function () { return view('auth.login'); })->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// HALAMAN DEPAN (Bisa diakses Anggota/Guest)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// GROUP ADMIN & PETUGAS
Route::prefix('admin')->middleware(['auth', 'role:admin,petugas'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Cuma Admin yang bisa akses menu User
    Route::resource('user', UserBackendController::class)->middleware('role:admin');
    
    // Admin & Petugas bisa akses Buku & Kategori
    Route::resource('buku', BukuController::class);
    Route::resource('categories', CategoryController::class);
});