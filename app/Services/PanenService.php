<?php 

namespace App\Services;

use App\Models\Panen;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Lahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class PanenService {
    public function getAll(): Collection {
        return Panen::with(['tanaman', 'lahan'])
        ->where('user_id', Auth::id())
        ->orderBy('tanggal_panen', 'desc')
        ->get();
    }

   public function create(array $data)
{
    // Gunakan DB Transaction agar kedua proses di bawah wajib sukses barengan
    return DB::transaction(function () use ($data) {
        
        // Perintah A: Catat data ke tabel panen (Ini yang sudah kamu punya)
        $panen = Panen::create([
            'user_id'      => Auth::id(),
            'lahan_id'     => $data['lahan_id'],
            'tanaman_id'   => $data['tanaman_id'],
            'jumlah_panen' => $data['jumlah_panen'],
            'tanggal_panen'=> $data['tanggal_panen'],
            'kualitas'     => $data['kualitas'],
        ]);

        // Perintah B: 🔥 INI KUNCINYA! Update tabel lahan agar tanaman_id menjadi NULL (Kosong)
        Lahan::where('id', $data['lahan_id'])
             ->where('user_id', Auth::id())
             ->update(['tanaman_id' => null]); // Cabut tanaman secara digital

        return $panen;
    });
}

    public function update(Panen $panen, array $data): Panen {
        if ($panen->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data panen ini.');
        }

        $panen->load(['lahan', 'tanaman']);
        $jumlahLama = $panen->jumlah_panen;
        $kualitasLama = $panen->kualitas;

        $panen->update($data);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'Ubah Panen',
            'description' => "Mengubah catatan panen {$panen->tanaman->nama_tanaman} di {$panen->lahan->nama_lahan}. Berubah dari [{$jumlahLama} Kg, {$kualitasLama}] menjadi [{$panen->jumlah_panen} Kg, {$panen->kualitas}].",
            'ip_address'  => request()->ip(),
        ]);

        return $panen;
    }


    public function delete(Panen $panen): bool {
        if ($panen->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data panen ini.');
        }
        $panen->load(['lahan', 'tanaman']);
        $namaTanaman = $panen->tanaman->nama_tanaman;
        $namaLahan = $panen->lahan->nama_lahan;
        $jumlah = $panen->jumlah_panen;

        $panen->deleteOrFail();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'Hapus Panen',
            'description' => "Menghapus catatan hasil panen varietas {$namaTanaman} di {$namaLahan} berjumlah {$jumlah} Kg.",
            'ip_address'  => request()->ip(),
        ]);

        return true;
    }
}