<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name')->unique(); // Nama kategori (e.g. Technology)
            $blueprint->string('slug')->unique(); // URL friendly (e.g. technology)
            $blueprint->string('icon')->nullable()->default('fas fa-folder'); // Class FontAwesome
            $blueprint->timestamps(); // Menciptakan created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};