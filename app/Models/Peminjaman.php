<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    // Tambahkan baris ini biar Laravel gak nyari 'peminjamen'
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id', 
        'buku_id', 
        'tgl_request', 
        'tgl_pinjam', 
        'tgl_kembali', 
        'status'
    ];

    // Sekalian pastiin relasinya ada biar gak error pas approval nanti
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}