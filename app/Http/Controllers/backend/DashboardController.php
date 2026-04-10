<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Buku;
use Illuminate\Http\Request;

class DashboardController extends Controller 
{
    public function index()
{
    $totalUser = User::count();
    $totalBuku = Buku::count();
    $totalPinjaman = 0; // Nanti tinggal ganti Peminjaman::count()

    // 1. Pastikan path view bener: 'welcome' atau 'pages.backend.index'
    // 2. Pakai compact() dengan benar (tanpa typo dan kurung berlebih)
    return view('pages.backend.dashboard', compact('totalUser', 'totalBuku', 'totalPinjaman'));
}
}