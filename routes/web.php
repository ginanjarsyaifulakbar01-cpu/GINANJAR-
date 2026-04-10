<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\frontend\FrontendController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\UserBackendController;
use App\Http\Controllers\backend\BukuController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\PeminjamanController;

/*
|--------------------------------------------------------------------------
| Web Routes - Perpustakaan Digital (GinxAdmin)
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES (Landing Page) ---
Route::get('/', [FrontendController::class, 'landing'])->name('landing');

// --- GUEST ROUTES (Hanya bisa diakses kalau BELUM login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- AUTH SHARED ROUTES (Semua User Login) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// --- BACKEND ROUTES (Role: Admin & Petugas) ---
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin,petugas']], function () {
    
    // 1. Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', function () { return view('pages.backend.profile'); })->name('admin.profile');
    
    // 2. MODUL PEMINJAMAN (Sisi Admin - Approval & Monitoring)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{id}/kembali', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembali');

    // 3. MASTER DATA (CRUD Buku, Kategori, & User)
    Route::resource('buku', BukuController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('user', UserBackendController::class)->middleware('role:admin');
});

// --- FRONTEND ROUTES (Role: Anggota / User Biasa) ---
Route::middleware('auth')->group(function () {
    
    // 1. Navigasi Halaman User
    Route::get('/home', [FrontendController::class, 'index'])->name('home'); 
    Route::get('/katalog', [FrontendController::class, 'katalog'])->name('katalog');
    Route::get('/profile', function () { return view('pages.frontend.profile'); })->name('profile');
    
    // 2. RIWAYAT PEMINJAMAN (Halaman yang barusan kita buat)
    Route::get('/riwayat-pinjam', [FrontendController::class, 'riwayatPinjam'])->name('riwayat.pinjam');
    
    // 3. Detail & Aksi Pinjam Buku
    Route::get('/buku/{id}', [FrontendController::class, 'show'])->name('buku.detail');
    Route::post('/buku/{id}/pinjam', [FrontendController::class, 'pinjamBuku'])->name('buku.pinjam');
});