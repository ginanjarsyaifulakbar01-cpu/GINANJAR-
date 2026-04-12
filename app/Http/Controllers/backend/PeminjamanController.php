<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * BACKEND: List Semua Transaksi Peminjaman
     */
    public function index()
    {
        $data = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);
        return view('pages.backend.peminjaman.index', compact('data'));
    }

    /**
     * FRONTEND: Member mengajukan peminjaman buku
     */
    public function ajukan(Request $request, $buku_id)
    {
        $buku = Buku::findOrFail($buku_id);
        
        if ($buku->stok <= 0) {
            return back()->with('error', '❌ Stok buku habis.');
        }

        $exists = Peminjaman::where('user_id', Auth::id())
                            ->where('buku_id', $buku_id)
                            ->whereIn('status', ['pending', 'pinjam', 'proses_kembali'])
                            ->first();

        if ($exists) {
            return back()->with('error', '⚠️ Anda masih memiliki transaksi aktif untuk buku ini.');
        }

        $tgl_pinjam = $request->tgl_pinjam ? Carbon::parse($request->tgl_pinjam) : now();

        Peminjaman::create([
            'user_id'      => Auth::id(),
            'buku_id'      => $buku_id,
            'tgl_pinjam'   => $tgl_pinjam,
            'tgl_kembali'  => $tgl_pinjam->copy()->addDays(7),
            'status'       => 'pending',
            'status_denda' => 'no_denda',
            'total_denda'  => 0
        ]);

        return redirect()->route('riwayat.pinjam')
                         ->with('success', '✅ Peminjaman berhasil diajukan!');
    }

    /**
     * BACKEND: Admin menyetujui atau menolak pengajuan pinjam baru
     */
    public function review(Request $request, $id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($request->action == 'setuju') {
            if ($p->buku->stok <= 0) {
                return back()->with('error', '❌ Gagal setuju, stok buku habis.');
            }

            $p->update(['status' => 'pinjam']);
            $p->buku->decrement('stok');
            return back()->with('success', '✅ Peminjaman disetujui.');
        }

        $p->update(['status' => 'ditolak']);
        return back()->with('info', 'ℹ️ Peminjaman ditolak.');
    }

    /**
     * FRONTEND: Member mengembalikan buku (Sistem cek keterlambatan & denda)
     */
    public function prosesKembalikan($id)
    {
        $p = Peminjaman::findOrFail($id);
        $tgl_sekarang = Carbon::now();
        $tgl_jatuh_tempo = Carbon::parse($p->tgl_kembali);

        // Cek jika hari ini melewati tanggal kembali
        if ($tgl_sekarang->gt($tgl_jatuh_tempo)) {
            $selisih_hari = $tgl_sekarang->diffInDays($tgl_jatuh_tempo);
            $total_denda = $selisih_hari * 5000;

            $p->update([
                'total_denda'  => $total_denda,
                'status_denda' => 'belum_bayar',
            ]);
            
            return back()->with('error', '💸 Terlambat! Silakan bayar denda Rp ' . number_format($total_denda));
        }

        // Jika tidak telat, langsung masuk proses verifikasi admin
        $p->update(['status' => 'proses_kembali']);
        return back()->with('success', '✅ Permintaan pengembalian terkirim.');
    }

    /**
     * FRONTEND: Member upload bukti transfer denda
     */
    public function bayarDenda(Request $request, $id)
    {
        $request->validate(['bukti' => 'required|image|max:2048']);
        $p = Peminjaman::findOrFail($id);

        if ($request->hasFile('bukti')) {
            // Hapus bukti lama jika member re-upload sebelum diverifikasi
            if ($p->bukti_bayar) {
                Storage::disk('public')->delete($p->bukti_bayar);
            }

            $path = $request->file('bukti')->store('bukti_bayar', 'public');
            
            $p->update([
                'bukti_bayar'  => $path,
                'status_denda' => 'pending_admin',
                'status'       => 'proses_kembali' 
            ]);

            return back()->with('success', '📸 Bukti diunggah! Menunggu verifikasi.');
        }
        return back()->with('error', 'Gagal upload.');
    }

    /**
     * BACKEND: Admin memverifikasi pengembalian buku & bukti denda
     */
    public function review_kembali(Request $request, $id)
    {
        $p = Peminjaman::findOrFail($id);

        // JIKA ADMIN SETUJU
        if ($request->action == 'setuju') {
            $p->update([
                'status' => 'dikembalikan',
                'tgl_realisasi_kembali' => now(),
                'status_denda' => ($p->total_denda > 0) ? 'lunas' : 'no_denda'
            ]);
            
            // Stok buku bertambah kembali
            $p->buku->increment('stok');
            return back()->with('success', '✅ Buku kembali & stok bertambah.');
        }

        // JIKA ADMIN TOLAK BUKTI BAYAR
        if ($request->action == 'tolak_denda') {
            // 1. Hapus file gambar dari folder storage agar tidak menumpuk sampah
            if ($p->bukti_bayar) {
                Storage::disk('public')->delete($p->bukti_bayar);
            }

            // 2. Set kolom bukti_bayar jadi NULL agar gambar hilang dari tabel dashboard
            $p->update([
                'status_denda' => 'belum_bayar', 
                'status'       => 'pinjam',
                'bukti_bayar'  => null 
            ]);

            return back()->with('error', '❌ Bukti denda ditolak & dihapus.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}