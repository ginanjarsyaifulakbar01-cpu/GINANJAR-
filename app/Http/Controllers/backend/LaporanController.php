<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan utama dengan statistik pendapatan denda.
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi Query dasar
        $query = Peminjaman::with(['user', 'buku'])->latest();

        // 2. Filter Tanggal
        if ($request->tgl_mulai && $request->tgl_selesai) {
            $query->whereBetween('tgl_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // 3. Filter Status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // 4. Ambil data dengan Pagination
        $data = $query->paginate(15);

        /**
         * 5. PERHITUNGAN TOTAL DENDA (LIVE)
         * Kita gunakan koleksi data yang sudah ditarik (terfilter) 
         * lalu jumlahkan menggunakan accessor 'denda' dari Model.
         */
        $total_pendapatan_denda = $data->sum(function($item) {
            return $item->denda; // Memanggil getDendaAttribute dari Model
        });

        return view('pages.backend.laporan.index', compact('data', 'total_pendapatan_denda'));
    }

    /**
     * Export data laporan ke format View/PDF dengan filter yang sama.
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

        /**
         * Hitung total denda LIVE untuk PDF
         */
        $total_denda = $laporan->sum(function($item) {
            return $item->denda; 
        });
        
        $tgl_cetak = Carbon::now()->translatedFormat('d F Y');

        $data_pdf = [
            'laporan'       => $laporan,
            'total_denda'   => $total_denda,
            'tgl_cetak'     => $tgl_cetak,
            'filter_tgl'    => $request->tgl_mulai ? $request->tgl_mulai . ' s/d ' . $request->tgl_selesai : 'Semua Waktu',
            'filter_status' => $request->status ?? 'Semua Status'
        ];

        return view('pages.backend.laporan.pdf', $data_pdf);
    }
}