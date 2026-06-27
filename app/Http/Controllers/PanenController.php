<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panen;
use App\Models\Lahan;
use App\Models\Tanaman;
use App\Services\PanenService;
use Illuminate\Support\Facades\Auth;

class PanenController extends Controller
{
    protected $panenService;

    // Dependency Injection untuk PanenService
    public function __construct(PanenService $panenService)
    {
        $this->panenService = $panenService;
    }

    /**
     * Menampilkan daftar panen khusus user yang login
     */
    public function index()
    {
        $userId = Auth::id();

        // Mengambil data panen yang otomatis tersaring sesuai user_id di dalam Service
        $daftarPanen = $this->panenService->getAll();

        // Mengambil data lahan milik user untuk dropdown pilihan di form
        $daftarLahan = Lahan::where('user_id', $userId)->get();

        // Mengambil data tanaman yang tertanam di lahan milik user saja
        $daftarTanaman = Tanaman::whereHas('lahan', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return view('panen', compact('daftarPanen', 'daftarLahan', 'daftarTanaman'));
    }

    /**
     * Menyimpan data panen baru
     */
    public function store(Request $request)
    {
        $dataValidasi = $request->validate([
            'lahan_id'     => 'required|exists:lahan,id',
            'tanaman_id'   => 'required|exists:tanaman,id',
            'jumlah_panen' => 'required|integer|min:1',
            'tanggal_panen'=> 'required|date',
            'kualitas'     => 'required|string|max:50', // Misal: Grade A, Grade B, dll
        ]);

        // Eksekusi pembuatan data + Audit Log lewat Service
        $this->panenService->create($dataValidasi);

        return redirect()->route('panen')
                         ->with('success', 'Catatan hasil panen berhasil ditambahkan!');
    }

    /**
     * Memperbarui data catatan panen
     */
    public function update(Request $request, Panen $panen)
    {
        // Proteksi berlapis agar tidak bisa tembak ID via Postman/URL
        if ($panen->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $dataValidasi = $request->validate([
            'lahan_id'     => 'required|exists:lahan,id',
            'tanaman_id'   => 'required|exists:tanaman,id',
            'jumlah_panen' => 'required|integer|min:1',
            'tanggal_panen'=> 'required|date',
            'kualitas'     => 'required|string|max:50',
        ]);

        // Eksekusi pembaruan data + Audit Log lewat Service
        $this->panenService->update($panen, $dataValidasi);

        return redirect()->route('panen')
                         ->with('success', 'Catatan panen berhasil diperbarui!');
    }

    /**
     * Menghapus data catatan panen
     */
    public function destroy(Panen $panen)
    {
        // Proteksi berlapis sebelum menghapus
        if ($panen->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Eksekusi penghapusan data + Audit Log lewat Service
        $this->panenService->delete($panen);

        return redirect()->route('panen')
                         ->with('success', 'Catatan panen berhasil dihapus!');
    }
}