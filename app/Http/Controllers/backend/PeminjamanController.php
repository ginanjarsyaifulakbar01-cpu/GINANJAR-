<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import library PDF untuk cetak struk

class PeminjamanController extends Controller
{
    // ==========================================
    // BAGIAN 1: MANAJEMEN TRANSAKSI (BACKEND - ADMIN)
    // ==========================================

    /**
     * Menampilkan daftar semua transaksi untuk admin
     */
    public function index()
    {
        $data = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);

        $data->getCollection()->transform(function ($p) {
            $p->is_late = ($p->status === 'pinjam' && now()->startOfDay()->gt(Carbon::parse($p->tgl_kembali)->startOfDay()));
            return $p;
        });

        return view('pages.backend.peminjaman.index', compact('data'));
    }

    /**
     * Admin menyetujui atau menolak pengajuan pinjam baru
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
     * Admin memverifikasi pengembalian & mengunci nilai denda
     */
    public function review_kembali(Request $request, $id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($request->action == 'setuju') {
            $dendaFinal = $p->denda; 
            $status_denda_final = ($dendaFinal > 0) ? 'lunas' : 'no_denda';

            $p->update([
                'status' => 'dikembalikan',
                'tgl_realisasi_kembali' => now(),
                'total_denda' => $dendaFinal,
                'status_denda' => $status_denda_final
            ]);
            
            $p->buku->increment('stok');
            return back()->with('success', '✅ Buku diterima. Status denda: ' . strtoupper($status_denda_final));
        }

        if ($request->action == 'tolak_denda') {
            if ($p->bukti_bayar) {
                Storage::disk('public')->delete($p->bukti_bayar);
            }
            $p->update([
                'status_denda' => 'belum_bayar', 
                'status'       => 'pinjam',
                'bukti_bayar'  => null 
            ]);
            return back()->with('error', '❌ Bukti denda ditolak. Anggota harus upload ulang.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    // ==========================================
    // BAGIAN 2: FITUR MEMBER (FRONTEND - ANGGOTA)
    // ==========================================

    /**
     * Anggota mengajukan peminjaman buku
     */
    public function ajukan(Request $request, $buku_id)
    {
        $buku = Buku::findOrFail($buku_id);
        if ($buku->stok <= 0) { return back()->with('error', '❌ Stok buku habis.'); }

        $exists = Peminjaman::where('user_id', Auth::id())
                            ->where('buku_id', $buku_id)
                            ->whereIn('status', ['pending', 'pinjam', 'proses_kembali'])
                            ->first();

        if ($exists) { return back()->with('error', '⚠️ Anda masih memiliki transaksi aktif untuk buku ini.'); }

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

        return redirect()->route('riwayat.pinjam')->with('success', '✅ Peminjaman berhasil diajukan!');
    }

    /**
     * Anggota memproses pengembalian buku
     */
    public function prosesKembalikan($id)
    {
        $p = Peminjaman::findOrFail($id);
        
        if ($p->is_terlambat) {
            $total_denda = $p->denda;

            $p->update([
                'total_denda'  => $total_denda,
                'status_denda' => 'belum_bayar',
            ]);
            return back()->with('error', '💸 Anda terlambat! Silakan upload bukti bayar denda Rp ' . number_format($total_denda, 0, ',', '.'));
        }

        $p->update(['status' => 'proses_kembali']);
        return back()->with('success', '✅ Permintaan pengembalian dikirim ke admin.');
    }

    /**
     * Anggota upload bukti bayar denda
     */
    public function bayarDenda(Request $request, $id)
    {
        $request->validate(['bukti' => 'required|image|max:2048']);
        $p = Peminjaman::findOrFail($id);

        if ($request->hasFile('bukti')) {
            if ($p->bukti_bayar) { Storage::disk('public')->delete($p->bukti_bayar); }
            $path = $request->file('bukti')->store('bukti_bayar', 'public');
            
            $p->update([
                'bukti_bayar'  => $path,
                'status_denda' => 'pending_admin',
                'status'       => 'proses_kembali' 
            ]);
            return back()->with('success', '📸 Bukti berhasil diunggah. Menunggu konfirmasi admin.');
        }
        return back()->with('error', 'Gagal mengunggah bukti.');
    }

    // ==========================================
    // BAGIAN 3: LAPORAN & STATISTIK
    // ==========================================

    /**
     * Menampilkan laporan peminjaman dengan filter
     */
    public function laporan(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku'])->latest();

        if ($request->tgl_mulai && $request->tgl_selesai) {
            $query->whereBetween('tgl_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $total_pendapatan_denda = Peminjaman::where('status_denda', 'lunas')->sum('total_denda');
        $data = $query->paginate(15);
        $total_transaksi = $data->total();

        return view('pages.backend.laporan.index', compact('data', 'total_pendapatan_denda', 'total_transaksi'));
    }

    /**
     * Export laporan ke PDF
     */
    public function cetakPdf(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku'])->latest();
        
        if ($request->tgl_mulai && $request->tgl_selesai) {
            $query->whereBetween('tgl_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        }
        if ($request->status) { 
            $query->where('status', $request->status); 
        }

        $laporan = $query->get();
        $total_denda = $laporan->where('status_denda', 'lunas')->sum('total_denda');
        $tgl_cetak = Carbon::now()->translatedFormat('d F Y');

        return view('pages.backend.laporan.pdf', [
            'laporan'       => $laporan,
            'total_denda'   => $total_denda,
            'tgl_cetak'     => $tgl_cetak,
            'filter_tgl'    => $request->tgl_mulai ? $request->tgl_mulai . ' s/d ' . $request->tgl_selesai : 'Semua Waktu',
            'filter_status' => $request->status ?? 'Semua Status'
        ]);
    }

    /**
     * Cetak Struk Thermal Peminjaman (80mm)
     * Menampilkan detail transaksi untuk bukti fisik anggota
     */
    public function cetakStruk($id)
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])->findOrFail($id);
        
        // Load view struk dan atur ukuran kertas thermal 80mm
        $pdf = Pdf::loadView('pages.frontend.peminjaman.struk', compact('peminjaman'))
            ->setPaper([0, 0, 226, 600], 'portrait') // Ukuran custom 80mm
            ->setOptions([
                'defaultFont' => 'Courier',
                'isRemoteEnabled' => true,
                'enable_html5_parser' => true,
            ]);

        return $pdf->stream('struk-peminjaman-' . $peminjaman->id . '.pdf');
    }
}