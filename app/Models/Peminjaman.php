<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    // Paksa nama tabel agar tidak menjadi 'peminjamen'
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_realisasi_kembali',
        'total_denda',
        'status',
        'status_denda',
        'bukti_bayar'
    ];

    /**
     * Casts: Mengubah string tanggal dari DB menjadi objek Carbon secara otomatis
     */
    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali' => 'date',
        'tgl_realisasi_kembali' => 'date',
    ];

    /**
     * Accessor Denda: Menghitung denda secara otomatis dan real-time
     * Cara panggil di Blade: {{ $p->denda }}
     */
    public function getDendaAttribute()
    {
        $tarifPerHari = 5000;

        // 1. Jika status dikembalikan, gunakan nilai denda yang sudah di-lock (disimpan) di DB
        if ($this->status === 'dikembalikan') {
            return max(0, $this->total_denda ?? 0);
        }

        // 2. Jika status AKTIF (pinjam atau proses_kembali), hitung denda LIVE berdasarkan hari ini
        // Ini memastikan di Dashboard Admin/User denda akan muncul meskipun di DB masih 0
        if (in_array($this->status, ['pinjam', 'proses_kembali'])) {
            $tglHarusKembali = Carbon::parse($this->tgl_kembali)->startOfDay();
            $hariIni = Carbon::now()->startOfDay();

            if ($hariIni->gt($tglHarusKembali)) {
                $selisihHari = $hariIni->diffInDays($tglHarusKembali);
                return $selisihHari * $tarifPerHari;
            }
        }

        return 0;
    }

    /**
     * Helper: Mengecek apakah transaksi ini sudah terlambat
     * Digunakan untuk memicu label "TERLAMBAT" di UI
     */
    public function getIsTerlambatAttribute()
    {
        // Jika sudah kembali, cek apakah saat dikembalikan ada denda
        if ($this->status === 'dikembalikan') {
            return $this->total_denda > 0;
        }
        
        // Jika belum kembali, bandingkan hari ini dengan tgl_kembali
        return Carbon::now()->startOfDay()->gt(Carbon::parse($this->tgl_kembali)->startOfDay());
    }

    /* --- RELASI --- */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}