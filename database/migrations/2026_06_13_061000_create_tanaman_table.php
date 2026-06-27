<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman'); // Contoh: Cabai Merah, Sawi Hijau, Mangga
            $table->enum('kategori', ['Sayuran', 'Buah-buahan']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanaman');
    }
};