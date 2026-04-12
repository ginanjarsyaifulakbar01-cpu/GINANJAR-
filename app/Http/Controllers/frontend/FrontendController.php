<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Category;
use App\Models\Peminjaman;
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
        $query = Buku::query()->with('category')->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $bukus = $query->paginate(12);
        return view('pages.frontend.katalog', compact('categories', 'bukus'));
    }

    /**
     * 4. Tampilan Detail Buku
     */
    public function detail($id)
    {
        $buku = Buku::with('category')->findOrFail($id);
        $related_books = Buku::where('category_id', $buku->category_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('pages.frontend.show', compact('buku', 'related_books'));
    }

    /**
     * 5. Proses Pengajuan Peminjaman
     * MODE SIMULASI: Sinkron dengan Backend agar tanggal tidak berubah saat disetujui.
     */
    public function pinjam(Request $request, $id)
    {
        $request->validate([
            'tgl_pinjam' => 'required|date',
            'durasi'     => 'required|integer|min:7',
        ], [
            'durasi.min' => 'Durasi peminjaman minimal adalah 7 hari.'
        ]);

        $buku = Buku::findOrFail($id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku sedang habis.');
        }

        // Olah data tanggal
        $tanggalMulai = Carbon::parse($request->tgl_pinjam);
        $durasi = (int) $request->durasi;

        // Simpan Transaksi Peminjaman (Status tetap Pending)
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $id,
            'tgl_pinjam' => $tanggalMulai,
            'tgl_kembali' => $tanggalMulai->copy()->addDays($durasi),
            'status' => 'pending',
            'status_denda' => 'no_denda'
        ]);

        // Catatan: Stok dikurangi di Backend saat Admin klik "Setuju", 
        // agar tidak terjadi pengurangan stok palsu jika admin menolak pengajuan.

        return redirect()->route('peminjaman.detail', $peminjaman->id)
                         ->with('success', 'Peminjaman diajukan! Menunggu persetujuan admin.');
    }

    /**
     * 6. Tampilan Detail Peminjaman
     */
    public function detailPeminjaman($id)
    {
        $pinjam = Peminjaman::with(['buku', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pages.frontend.detail_peminjaman', compact('pinjam'));
    }

    /**
     * 7. Riwayat Pinjam User
     */
    public function riwayatPinjam()
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
                                ->with('buku')
                                ->latest()
                                ->get();

        return view('pages.frontend.peminjaman', compact('peminjaman'));
    }
}