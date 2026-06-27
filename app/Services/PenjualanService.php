<?php 

namespace App\Services;

use App\Models\Penjualan;
use App\Models\Panen;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class PenjualanService {

    public function getAll(): Collection {
        return Penjualan::with(['panen.tanaman', 'panen.lahan'])
            ->where('user_id', Auth::id())
            ->orderBy('tanggal_penjualan', 'desc')
            ->get();
    }

    public function create(array $data): Penjualan 
    {
        return DB::transaction(function () use ($data) {
            $userId = Auth::id();

            // 1. Ambil data panen yang dipilih beserta kunci row (lockForUpdate) demi keamanan data stok
            $panen = Panen::with(['tanaman', 'lahan'])
                ->where('id', $data['panen_id'])
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            // 🛑 VALIDASI 1: Cek apakah jumlah terjual melebihi stok panen yang tersedia
            if ($data['jumlah_terjual'] > $panen->jumlah_panen) {
                throw ValidationException::withMessages([
                    'jumlah_terjual' => "Gagal! Stok panen yang tersedia hanya {$panen->jumlah_panen} Kg, tidak bisa menjual {$data['jumlah_terjual']} Kg."
                ]);
            }

            // 2. Hitung komponen finansial profit bersih
            $biaya = $data['biaya_operasional'] ?? 0;
            $profitBersih = $data['total_pendapatan'] - $biaya;

            // 3. Simpan transaksi penjualan ke database
            $penjualan = Penjualan::create([
                'user_id'           => $userId,
                'panen_id'          => $data['panen_id'],
                'jumlah_terjual'    => $data['jumlah_terjual'],
                'total_pendapatan'  => $data['total_pendapatan'],
                'total_profit'      => $profitBersih,
                'tanggal_penjualan' => $data['tanggal_penjualan'],
            ]);

            // 📉 KUNCI UTAMA SINKRONISASI: Kurangi jumlah stok di tabel panen!
            $panen->decrement('jumlah_panen', $data['jumlah_terjual']);

            // 4. Catat ke Audit Log sistem
            AuditLog::create([
                'user_id'     => $userId,
                'activity'    => 'Tambah Penjualan',
                'description' => "Membukukan penjualan {$panen->tanaman->nama_tanaman} sebanyak {$data['jumlah_terjual']} Kg. Stok panen berkurang. Sisa stok kini: {$panen->jumlah_panen} Kg.",
                'ip_address'  => request()->ip(),
            ]);

            return $penjualan;
        });
    }

    public function update(Penjualan $penjualan, array $data): Penjualan 
    {
        if ($penjualan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data penjualan ini.');
        }

        return DB::transaction(function () use ($penjualan, $data) {
            $userId = Auth::id();
            
            // Ambil data panen terkait transaksi lama & transaksi baru
            $panenLama = Panen::where('id', $penjualan->panen_id)->where('user_id', $userId)->lockForUpdate()->firstOrFail();
            
            // Kembalikan dulu stok panen lama sebelum kalkulasi ulang
            $panenLama->increment('jumlah_panen', $penjualan->jumlah_terjual);

            // Jika petani mengubah batch panen ke batch yang berbeda
            $panenTarget = ($penjualan->panen_id == $data['panen_id']) 
                ? $panenLama 
                : Panen::where('id', $data['panen_id'])->where('user_id', $userId)->lockForUpdate()->firstOrFail();

            // 🛑 VALIDASI 2: Cek apakah jumlah terjual yang baru muat dengan stok setelah dikembalikan
            if ($data['jumlah_terjual'] > $panenTarget->jumlah_panen) {
                throw ValidationException::withMessages([
                    'jumlah_terjual' => "Gagal Perbarui! Batasan stok aktual panen saat ini hanya sebesar {$panenTarget->jumlah_panen} Kg."
                ]);
            }

            // Potong stok dengan nilai penjualan yang baru
            $panenTarget->decrement('jumlah_panen', $data['jumlah_terjual']);

            // Hitung ulang profit
            $biaya = $data['biaya_operasional'] ?? 0;
            $data['total_profit'] = $data['total_pendapatan'] - $biaya;

            // Eksekusi update data penjualan
            $penjualan->update($data);

            AuditLog::create([
                'user_id'     => $userId,
                'activity'    => 'Ubah Penjualan',
                'description' => "Mengoreksi transaksi penjualan. Stok panen disesuaikan ulang.",
                'ip_address'  => request()->ip(),
            ]);

            return $penjualan;
        });
    }

    public function delete(Penjualan $penjualan): bool 
    {
        if ($penjualan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data penjualan ini.');
        }

        return DB::transaction(function () use ($penjualan) {
            // 📈 KUNCI PEMULIHAN: Jika data penjualan dihapus, kembalikan berat Kg ke tabel panen semula
            $panen = Panen::where('id', $penjualan->panen_id)
                ->where('user_id', Auth::id())
                ->first();

            if ($panen) {
                $panen->increment('jumlah_panen', $penjualan->jumlah_terjual);
            }

            $penjualan->deleteOrFail();

            AuditLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Hapus Penjualan',
                'description' => "Menghapus catatan penjualan. Jumlah volume sebanyak {$penjualan->jumlah_terjual} Kg dikembalikan ke stok log panen.",
                'ip_address'  => request()->ip(),
            ]);

            return true;
        });
    }
}