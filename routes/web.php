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

// --- 1. PUBLIC & GUEST ---
Route::get('/', [FrontendController::class, 'landing'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- 2. AUTHENTICATED ROUTES (Semua User yang Login) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- FRONTEND ROUTES (User/Anggota) ---
    Route::get('/home', [FrontendController::class, 'index'])->name('home');
    Route::get('/katalog', [FrontendController::class, 'katalog'])->name('katalog');
    Route::get('/buku/{id}/detail', [FrontendController::class, 'detail'])->name('buku.detail');
    
    Route::get('/profile', function () {
        return view('pages.frontend.profile');
    })->name('profile');
    
    Route::get('/riwayat-pinjam', [FrontendController::class, 'riwayatPinjam'])->name('riwayat.pinjam');
    Route::get('/peminjaman/detail/{id}', [FrontendController::class, 'detailPeminjaman'])->name('peminjaman.detail');

    // FITUR CETAK STRUK (Dikeluarkan dari Admin agar bisa diakses Member/Siswa)
    // URL Sekarang: http://127.0.0.1:8000/peminjaman/cetak/1
    Route::get('/peminjaman/cetak/{id}', [PeminjamanController::class, 'cetakStruk'])->name('peminjaman.cetak');

    // ALUR PEMINJAMAN (FE)
    Route::post('/buku/{id}/ajukan', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan');
    Route::post('/peminjaman/{id}/proses-kembali', [PeminjamanController::class, 'prosesKembalikan'])->name('peminjaman.proses_kembali');
    Route::post('/peminjaman/{id}/bayar-denda', [PeminjamanController::class, 'bayarDenda'])->name('peminjaman.bayar_denda');


    // --- 3. BACKEND ROUTES (Admin & Petugas) ---
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin,petugas']], function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/profile-admin', function () {
            return view('pages.backend.profile');
        })->name('admin.profile');

        // MANAJEMEN PEMINJAMAN (BE)
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{id}/review', [PeminjamanController::class, 'review'])->name('peminjaman.review');
        Route::post('/peminjaman/{id}/review-kembali', [PeminjamanController::class, 'review_kembali'])->name('peminjaman.review_kembali');

        // FITUR LAPORAN (BE)
        Route::get('/laporan-peminjaman', [PeminjamanController::class, 'laporan'])->name('laporan.index');
        Route::get('/laporan-peminjaman/cetak', [PeminjamanController::class, 'cetakPdf'])->name('laporan.cetak');

        // MASTER DATA
        Route::resource('buku', BukuController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('user', UserBackendController::class)->middleware('role:admin');
    });
});