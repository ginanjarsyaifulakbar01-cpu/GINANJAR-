<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan import ini

class Buku extends Model
{
    protected $table = 'bukus';
    
    // Semua kolom boleh diisi
    protected $guarded = [];

    /**
     * Relasi ke model Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relasi ke model Peminjaman
     * Method ini WAJIB ADA agar ->withCount('peminjaman') di Controller bisa jalan
     */
    public function peminjaman(): HasMany
    {
        // Hubungkan id di tabel bukus ke buku_id di tabel peminjamans
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}