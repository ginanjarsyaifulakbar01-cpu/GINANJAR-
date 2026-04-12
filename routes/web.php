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
| Web Routes - Perpustakaan Digital
|--------------------------------------------------------------------------
*/

// --- 1. PUBLIC & GUEST ---
Route::get('/', [FrontendController::class, 'landing'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- 2. AUTHENTICATED ROUTES (Semua User yang Login) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- FRONTEND ROUTES (User/Anggota) ---
    Route::get('/home', [FrontendController::class, 'index'])->name('home'); 
    Route::get('/katalog', [FrontendController::class, 'katalog'])->name('katalog');
    Route::get('/buku/{id}', [FrontendController::class, 'detail'])->name('buku.detail');
    Route::get('/profile', function () { return view('pages.frontend.profile'); })->name('profile');
    Route::get('/riwayat-pinjam', [FrontendController::class, 'riwayatPinjam'])->name('riwayat.pinjam');
    Route::get('/peminjaman/detail/{id}', [FrontendController::class, 'detailPeminjaman'])->name('peminjaman.detail'); 
    
    /** 
     * ALUR PEMINJAMAN (FE)
     * Menggunakan PeminjamanController agar logika terpusat
     */
    Route::post('/buku/{id}/ajukan', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan');
    Route::post('/peminjaman/{id}/proses-kembali', [PeminjamanController::class, 'prosesKembalikan'])->name('peminjaman.proses_kembali');
    Route::post('/peminjaman/{id}/bayar-denda', [PeminjamanController::class, 'bayarDenda'])->name('peminjaman.bayar_denda');


    // --- 3. BACKEND ROUTES (Admin & Petugas) ---
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin,petugas']], function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/profile-admin', function () { return view('pages.backend.profile'); })->name('admin.profile');
        
        // MANAJEMEN PEMINJAMAN (BE)
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        
        // Approve Pinjam (Pending -> Pinjam)
        Route::post('/peminjaman/{id}/review', [PeminjamanController::class, 'review'])->name('peminjaman.review');
        
        // Approve Kembali (Proses Kembali -> Dikembalikan) + Verifikasi Foto Denda
        Route::post('/peminjaman/{id}/review-kembali', [PeminjamanController::class, 'review_kembali'])->name('peminjaman.review_kembali');

        // MASTER DATA
        Route::resource('buku', BukuController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('user', UserBackendController::class)->middleware('role:admin');
    });
});
