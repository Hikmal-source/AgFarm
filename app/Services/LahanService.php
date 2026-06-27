<?php 

namespace App\Services;

use App\Models\Lahan;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class LahanService {
    public function getAll(): Collection {
        return  Lahan::with(['tanaman', 'user'])->where('user_id', Auth::id())->get();
    }

    public function create(array $data): Lahan {
        $data['user_id'] = Auth::id();
        $lahan = Lahan::create($data);
        AuditLog::create([
            'user_id'     => Auth::id(), 
            'activity'    => 'Tambah Lahan',
            'description' => "Berhasil membuat lahan baru bernama: {$lahan->nama_lahan} di {$lahan->lokasi_blok}.",
            'ip_address'  => request()->ip(),
        ]);
        return $lahan;
    }

    public function update(Lahan $lahan, array $data): Lahan {
        abort_if($lahan->user_id !== Auth::id(), 403, 'Anda tidak memiliki akses ke lahan ini.');

        $nameOldlahan = $lahan->nama_lahan;
        $lahan->update($data);
        AuditLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'Ubah Lahan',
            'description' => "Mengubah data lahan '{$nameOldlahan}' (ID: {$lahan->id}) menjadi nama: {$lahan->nama_lahan} di {$lahan->lokasi_blok}.",
            'ip_address'  => request()->ip(),
        ]);

        return $lahan;
    }

    public function delete(Lahan $lahan): bool {
        abort_if($lahan->user_id !== Auth::id(), 403, 'Anda tidak memiliki akses ke lahan ini.');
        $namaLahanYangDihapus = $lahan->nama_lahan;
        $blokYangDihapus = $lahan->lokasi_blok;
        $lahan->deleteOrFail();
        AuditLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'Hapus Lahan',
            'description' => "Menghapus lahan bernama: {$namaLahanYangDihapus} yang berada di {$blokYangDihapus}.",
            'ip_address'  => request()->ip(),
        ]);

        return true;
    }
}