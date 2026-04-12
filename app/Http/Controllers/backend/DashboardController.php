<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman; 
use Illuminate\Http\Request;

class DashboardController extends Controller 
{
    public function index()
    {
        // 1. Hitung Statistik Utama
        $totalUser = User::count();
        $totalBuku = Buku::count();
        $totalPinjaman = Peminjaman::count(); 

        // 2. Ambil 5 Buku Terpopuler berdasarkan jumlah peminjaman
        // Ini membutuhkan method peminjaman() di Model Buku
        $bukuPopuler = Buku::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->take(5)
            ->get();

        // 3. Kirim data ke view dashboard
        return view('pages.backend.dashboard', compact(
            'totalUser', 
            'totalBuku', 
            'totalPinjaman', 
            'bukuPopuler'
        ));
    }
}