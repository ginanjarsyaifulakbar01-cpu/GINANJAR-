<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
            $table->date('tgl_pinjam')->nullable();
            $table->date('tgl_kembali')->nullable();
            $table->date('tgl_realisasi_kembali')->nullable();
            $table->integer('total_denda')->default(0);
            $table->string('bukti_bayar')->nullable(); 
            
            // PERBAIKAN: Ubah 'n/a' jadi 'no_denda' agar sinkron dengan Controller
            $table->enum('status_denda', [
                'no_denda', 
                'belum_bayar', 
                'pending_admin', 
                'lunas'
            ])->default('no_denda');

            $table->enum('status', [
                'pending', 
                'pinjam', 
                'dikembalikan', 
                'ditolak', 
                'proses_kembali'
            ])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};