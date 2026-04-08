<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buku extends Model
{
    protected $table = 'bukus';
    
    // Ini berarti semua kolom boleh diisi (judul, penulis, category_id, dll)
    protected $guarded = [];

    /**
     * Relasi ke model Category
     * Hubungkan category_id di tabel bukus ke id di tabel categories
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}