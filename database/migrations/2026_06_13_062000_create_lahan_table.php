<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke tabel tanaman yang dibuat pertama tadi
            $table->foreignId('tanaman_id')->nullable()->constrained('tanaman')->onDelete('set null');
            
            $table->string('nama_lahan'); // Contoh: Lahan Utama, Lahan Atas
            $table->string('lokasi_blok'); // Contoh: Blok A, Blok B
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lahan');
    }
};