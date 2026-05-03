<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

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

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali' => 'date',
        'tgl_realisasi_kembali' => 'date',
        'total_denda' => 'integer',
    ];

    /**
     * Accessor Denda: Menghitung denda secara otomatis.
     * Logika: Jika buku sudah balik, ambil nilai di DB. Jika belum, hitung LIVE.
     */
    public function getDendaAttribute()
    {
        // 1. Jika sudah selesai, prioritaskan nilai yang sudah 'mati' di database
        if ($this->status === 'dikembalikan') {
            return $this->total_denda ?? 0;
        }

        // 2. Hitung Live untuk status 'pinjam' atau 'proses_kembali'
        $tglJatuhTempo = Carbon::parse($this->tgl_kembali)->startOfDay();
        $hariIni = Carbon::now()->startOfDay();

        if ($hariIni->gt($tglJatuhTempo)) {
            $selisihHari = $hariIni->diffInDays($tglJatuhTempo);
            return $selisihHari * 5000;
        }

        return 0;
    }

    /**
     * Helper Terlambat: Digunakan untuk memicu label "TERLAMBAT" di Blade
     */
    public function getIsTerlambatAttribute()
    {
        // Jika sudah kembali, cek apakah dulu pernah denda
        if ($this->status === 'dikembalikan') {
            return ($this->total_denda ?? 0) > 0;
        }
        
        // Jika masih dipinjam, bandingkan dengan waktu sekarang
        return Carbon::now()->startOfDay()->gt(Carbon::parse($this->tgl_kembali)->startOfDay());
    }

    /* --- RELASI --- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}