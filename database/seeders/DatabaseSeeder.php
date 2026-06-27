<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lahan;
use App\Models\Tanaman;
use App\Models\Panen;
use App\Models\Penjualan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Hikmal',
            'email' => 'hikmal@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Petani'
        ]);

        $lahan = Lahan::create([
            'user_id' => $user->id,
            'nama_lahan' => 'Lahan Utama',
            'lokasi_blok' => 'Blok A'
        ]);

        $tanaman = Tanaman::create([
            'nama_tanaman' => 'Tomat',
            'kategori' => 'Buah-Buahan'
        ]);

        $panen = Panen::create([
            'lahan_id' => $lahan->id,
            'tanaman_id' => $tanaman->id,
            'jumlah_panen' =>50,
            'tanggal_panen' => now()->subdays(2)
        ]);
        $penjualan = Penjualan::create([
            'panen_id' => $panen->id,
            'tanggal_penjualan' => now()->subDays(1),
            'jumlah_terjual' => 30,
            'total_pendapatan' => 10000,
            'total_profit' => 5000
        ]);
    }
}
