<?php

namespace App\Http\Controllers\backend; // Pastikan ini sesuai folder kamu

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
     * BACKEND: List Peminjaman
     */
    public function index()
    {
        $data = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);
        return view('pages.backend.peminjaman.index', compact('data'));
    }

    /**
     * FRONTEND: Action Ajukan Pinjam
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
     * BACKEND: Review Peminjaman (Approve/Reject)
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
     * FRONTEND: Proses Kembalikan (Cek Denda)
     */
    public function prosesKembalikan($id)
    {
        $p = Peminjaman::findOrFail($id);
        $tgl_sekarang = Carbon::now();
        $tgl_jatuh_tempo = Carbon::parse($p->tgl_kembali);

        if ($tgl_sekarang->gt($tgl_jatuh_tempo)) {
            $selisih_hari = $tgl_sekarang->diffInDays($tgl_jatuh_tempo);
            $total_denda = $selisih_hari * 5000;

            $p->update([
                'total_denda'  => $total_denda,
                'status_denda' => 'belum_bayar',
            ]);
            
            return back()->with('error', '💸 Terlambat! Silakan bayar denda Rp ' . number_format($total_denda));
        }

        $p->update(['status' => 'proses_kembali']);
        return back()->with('success', '✅ Permintaan pengembalian terkirim.');
    }

    /**
     * FRONTEND: Bayar Denda
     */
    public function bayarDenda(Request $request, $id)
    {
        $request->validate(['bukti' => 'required|image|max:2048']);
        $p = Peminjaman::findOrFail($id);

        if ($request->hasFile('bukti')) {
            if ($p->bukti_bayar) Storage::disk('public')->delete($p->bukti_bayar);
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
     * BACKEND: Review Pengembalian & Denda
     */
    public function review_kembali(Request $request, $id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($request->action == 'setuju') {
            $p->update([
                'status' => 'dikembalikan',
                'tgl_realisasi_kembali' => now(),
                'status_denda' => ($p->total_denda > 0) ? 'lunas' : 'no_denda'
            ]);
            $p->buku->increment('stok');
            return back()->with('success', '✅ Buku kembali & stok bertambah.');
        }

        if ($request->action == 'tolak_denda') {
            $p->update(['status_denda' => 'belum_bayar', 'status' => 'pinjam']);
            return back()->with('error', '❌ Bukti denda ditolak.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}
