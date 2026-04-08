<?php

namespace App\Http\Controllers\backend; // Pastikan folder 'backend' huruf kecil sesuai struktur kamu

use App\Http\Controllers\Controller; // Wajib import Controller inti
use App\Models\User;
use App\Models\Buku;
use Illuminate\Http\Request;

// Nama Class disamakan dengan nama file: DashboardController (pakai 'h')
class DashboardController extends Controller 
{
    public function index()
    {
        // Mengambil jumlah data real-time
        $totalUser = User::count();
        $totalBuku = Buku::count();
        $totalPinjaman = 0; // Nanti tinggal ganti Peminjaman::count()

        // Pastikan path view benar, contoh: 'pages.backend.dashboard' 
        // Sesuaikan dengan lokasi file blade dashboard kamu
        return view('welcome', compact('totalUser', 'totalBuku', 'totalPinjaman'));
    }
}