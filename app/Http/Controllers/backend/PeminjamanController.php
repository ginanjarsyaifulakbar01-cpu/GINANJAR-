<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Load data user dan buku biar nggak lemot
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
        return view('pages.backend.peminjaman.index', compact('peminjamans'));
    }

    public function approve($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        if ($pinjam->buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis, tidak bisa approve!');
        }

        $pinjam->update([
            'status' => 'disetujui',
            'tgl_pinjam' => Carbon::now(),
            'tgl_kembali' => Carbon::now()->addDays(7),
        ]);

        $pinjam->buku->decrement('stok');
        return back()->with('success', 'Peminjaman disetujui!');
    }

    public function reject($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $pinjam->update(['status' => 'ditolak']);
        return back()->with('success', 'Peminjaman ditolak!');
    }

    public function kembalikan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $pinjam->update(['status' => 'dikembalikan']);
        $pinjam->buku->increment('stok');
        return back()->with('success', 'Buku telah dikembalikan!');
    }
}