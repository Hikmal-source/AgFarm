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
        $user = User::firstOrCreate(
            ['email' => 'hikmal@gmail.com'],
            [
                'name' => 'Hikmal',
                'password' => bcrypt('password'),
                'role' => 'Petani',
            ]
        );

        $lahan = Lahan::firstOrCreate([
            'user_id' => $user->id,
            'nama_lahan' => 'Lahan Utama',
            'lokasi_blok' => 'Blok A',
        ]);

        $tanaman = Tanaman::firstOrCreate([
            'nama_tanaman' => 'Tomat',
            'kategori' => 'Buah-buahan',
        ]);

        $panen = Panen::firstOrCreate([
            'user_id' => $user->id,
            'lahan_id' => $lahan->id,
            'tanaman_id' => $tanaman->id,
            'jumlah_panen' => 50,
            'tanggal_panen' => now()->subDays(2),
            'kualitas' => 'Grade A',
        ]);

        Penjualan::firstOrCreate([
            'user_id' => $user->id,
            'panen_id' => $panen->id,
            'jumlah_terjual' => 30,
            'total_pendapatan' => 10000,
            'total_profit' => 5000,
            'tanggal_penjualan' => now()->subDays(1),
        ]);
    }
}
