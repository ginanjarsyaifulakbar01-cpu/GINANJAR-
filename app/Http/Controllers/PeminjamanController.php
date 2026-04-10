<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // 1. Tampilkan semua data peminjaman (biar Petugas bisa pantau)
    public function index()
    {
        // Ambil data terbaru, load data user dan buku biar nggak query berulang (Eager Loading)
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
        return view('pages.backend.peminjaman.index', compact('peminjamans'));
    }

    // 2. FUNGSI APPROVE (Setujui Peminjaman)
    public function approve($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->status !== 'pending') {
            return back()->with('error', 'Data ini sudah tidak dalam status pending!');
        }

        // Cek stok buku sekali lagi buat jaga-jaga
        if ($pinjam->buku->stok <= 0) {
            return back()->with('error', 'Gagal Approve! Stok buku ini sudah habis.');
        }

        // Update data peminjaman
        $pinjam->update([
            'status' => 'disetujui',
            'tgl_pinjam' => Carbon::now(),
            'tgl_kembali' => Carbon::now()->addDays(7), // Default pinjam 7 hari
        ]);

        // POTONG STOK BUKU
        $pinjam->buku->decrement('stok');

        return back()->with('success', 'Peminjaman disetujui, stok buku otomatis berkurang!');
    }

    // 3. FUNGSI REJECT (Tolak Peminjaman)
    public function reject($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        $pinjam->update(['status' => 'ditolak']);

        return back()->with('success', 'Permintaan peminjaman ditolak.');
    }

    // 4. FUNGSI KEMBALI (User Balikin Buku)
    public function kembalikan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->status !== 'disetujui') {
            return back()->with('error', 'Hanya buku yang berstatus disetujui yang bisa dikembalikan.');
        }

        $pinjam->update(['status' => 'dikembalikan']);

        // TAMBAH STOK BUKU KEMBALI
        $pinjam->buku->increment('stok');

        return back()->with('success', 'Buku sudah dikembalikan, stok bertambah lagi!');
    }
}