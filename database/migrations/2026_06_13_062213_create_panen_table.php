<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lahan_id')->constrained('lahan')->onDelete('cascade');
            $table->foreignId('tanaman_id')->constrained('tanaman')->onDelete('cascade');
            
            $table->integer('jumlah_panen'); // Berapa Kg
            $table->date('tanggal_panen');
            $table->string('kualitas'); // Contoh: Grade A, Grade B
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panen');
    }
};