<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bikin tabelnya dulu tanpa foreign key
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable(); // Bikin kolom biasa dulu
            $table->string('judul')->unique();
            $table->string('penulis');
            $table->year('tahun_terbit');
            $table->integer('stok')->default(0);
            $table->string('cover')->nullable(); 
            $table->timestamps();
        });

        // 2. Cek apakah tabel categories beneran ada, baru hubungkan
        if (Schema::hasTable('categories')) {
            Schema::table('bukus', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};