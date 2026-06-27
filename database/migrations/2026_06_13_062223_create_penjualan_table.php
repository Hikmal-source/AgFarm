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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            // Tambahkan ini agar tahu admin/petani mana yang menginput penjualan
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke tabel panen (Sudah benar dan mantap!)
            $table->foreignId('panen_id')->constrained('panen')->onDelete('cascade');
            
            $table->integer('jumlah_terjual'); // Dalam satuan Kg
            $table->bigInteger('total_pendapatan'); // Gross Revenue (Rp)
            $table->bigInteger('total_profit'); // Net Profit (Rp - bisa minus kalau rugi)
            $table->date('tanggal_penjualan');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};