<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Category;
use App\Models\Peminjaman; // Pastikan Model Peminjaman sudah dibuat
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    /**
     * 1. Tampilan Landing Page
     */
    public function landing()
    {
        return view('pages.frontend.landing');
    }

    /**
     * 2. Tampilan Dashboard Utama User
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Buku::with('category')->latest();

        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }

        $bukus = $query->take(8)->get();
        return view('pages.frontend.index', compact('bukus', 'categories'));
    }

    /**
     * 3. Tampilan Katalog Lengkap
     */
    public function katalog(Request $request)
    {
        $categories = Category::all();
        $query = Buku::with('category');

        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }

        $bukus = $query->latest()->get();
        return view('pages.frontend.katalog', compact('bukus', 'categories'));
    }

    /**
     * 4. Tampilan Detail Buku
     */
    public function show($id)
    {
        $buku = Buku::with('category')->findOrFail($id);
        
        $related_books = Buku::where('category_id', $buku->category_id)
                             ->where('id', '!=', $id)
                             ->take(4)
                             ->get();

        return view('pages.frontend.show', compact('buku', 'related_books'));
    }

    /**
     * 5. Fungsi Request Peminjaman (User Meminta Izin ke BE)
     */
    public function pinjamBuku(Request $request, $id)
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Ente harus login dulu buat pinjam buku!');
        }

        $buku = Buku::findOrFail($id);

        // 2. Cek Stok Buku
        if ($buku->stok <= 0) {
            return back()->with('error', 'Waduh, stok buku ini lagi kosong Ngab!');
        }

        // 3. Cek apakah user sedang meminjam atau sudah request buku yang sama & belum kelar
        $cekPinjam = Peminjaman::where('user_id', Auth::id())
                                ->where('buku_id', $id)
                                ->whereIn('status', ['pending', 'disetujui'])
                                ->first();

        if ($cekPinjam) {
            return back()->with('error', 'Ente sudah mengajukan pinjaman untuk buku ini, tunggu diproses ya!');
        }

        // 4. Buat data peminjaman dengan status 'pending'
        // Status ini yang nanti harus disetujui (Approve) oleh Petugas di Backend
        Peminjaman::create([
            'user_id'     => Auth::id(),
            'buku_id'     => $id,
            'tgl_request' => Carbon::now(),
            'status'      => 'pending', // Kuncinya di sini, status awal harus pending
        ]);

        return back()->with('success', 'Request peminjaman berhasil dikirim! Silakan hubungi petugas untuk approval.');
    }
    public function riwayatPinjam()
{
    $userId = Auth::id();
    
    // Buku yang masih dibawa (Disetujui tapi belum balik)
    $sedangDipinjam = Peminjaman::with('buku')
        ->where('user_id', $userId)
        ->where('status', 'disetujui')
        ->get();

    // Semua riwayat lainnya (Selesai, Ditolak, Pending)
    $riwayatLengkap = Peminjaman::with('buku')
        ->where('user_id', $userId)
        ->whereIn('status', ['dikembalikan', 'pending', 'ditolak'])
        ->latest()
        ->get();

    return view('pages.frontend.riwayat_pinjam', compact('sedangDipinjam', 'riwayatLengkap'));
}

}