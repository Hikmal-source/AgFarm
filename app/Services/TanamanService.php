<?php 

namespace App\Services;

use App\Models\Tanaman;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class TanamanService {
    public function getAll() {
        return Tanaman::with('lahan')->orderBy('nama_tanaman', 'asc')->get();
    }

    public function create(array $data): Tanaman {
        $tanaman = Tanaman::create($data);

        AuditLog::create([
            'user_id'     => Auth::id(), 
            'activity'    => 'Tambah Tanaman',
            'description' => "Berhasil menambahkan master data tanaman baru: {$tanaman->nama_tanaman} dengan kategori {$tanaman->kategori}.",
            'ip_address'  => request()->ip(),
        ]);
        return $tanaman;
    }

    public function update(Tanaman $tanaman, array $data): Tanaman {
        $namaLamaTanaman = $tanaman->nama_tanaman;
        $kategoriLama = $tanaman->kategori;

        $tanaman->update($data);

        // 💡 Audit Log: Catat perubahan data tanaman
        AuditLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'Ubah Tanaman',
            'description' => "Mengubah data tanaman '{$namaLamaTanaman}' (Kategori: {$kategoriLama}) menjadi nama: {$tanaman->nama_tanaman} (Kategori: {$tanaman->kategori}).",
            'ip_address'  => request()->ip(),
        ]);

        return $tanaman;
    }
    public function delete(Tanaman $tanaman): bool {
        $namaTanamanDihapus = $tanaman->nama_tanaman;
        $kategoriDihapus = $tanaman->kategori;

        // Proses hapus dari database
        $tanaman->deleteOrFail();

        // 💡 Audit Log: Catat penghapusan tanaman
        AuditLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'Hapus Tanaman',
            'description' => "Menghapus master data tanaman bernama: {$namaTanamanDihapus} dengan kategori {$kategoriDihapus}.",
            'ip_address'  => request()->ip(),
        ]);

        return true;
    }
}