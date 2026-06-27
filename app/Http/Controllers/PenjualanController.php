<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Panen;
use App\Services\PenjualanService;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    protected $penjualanService;

    // Masukkan PenjualanService ke controller via Constructor
    public function __construct(PenjualanService $penjualanService)
    {
        $this->penjualanService = $penjualanService;
    }

    /**
     * Menampilkan halaman utama transaksi penjualan
     */
    public function index()
    {
        $userId = Auth::id();
        $daftarPenjualan = $this->penjualanService->getAll();
        $daftarPanen = Panen::with(['tanaman', 'lahan'])
            ->where('user_id', $userId)
            ->orderBy('tanggal_panen', 'desc')
            ->get();

        return view('penjualan', compact('daftarPenjualan', 'daftarPanen'));
    }

    /**
     * Menyimpan transaksi kas penjualan baru
     */
    public function store(Request $request)
    {
        // Validasi input data dari form HTML
        $dataValidasi = $request->validate([
            'panen_id'          => 'required|exists:panen,id',
            'jumlah_terjual'    => 'required|integer|min:1',
            'total_pendapatan'  => 'required|integer|min:1', // Masuk ke total_pendapatan db
            'tanggal_penjualan' => 'required|date',
            'biaya_operasional' => 'nullable|integer|min:0', // 👈 Ditangkap di sini untuk bahan hitung profit!
        ]);

        // Kirim data ke Service untuk dikalkulasi & disimpan
        $this->penjualanService->create($dataValidasi);

        return redirect()->route('penjualan')
                         ->with('success', 'Transaksi penjualan berhasil dibukukan!');
    }

    /**
     * Memperbarui transaksi penjualan
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        if ($penjualan->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $dataValidasi = $request->validate([
            'panen_id'          => 'required|exists:panen,id',
            'jumlah_terjual'    => 'required|integer|min:1',
            'total_pendapatan'  => 'required|integer|min:1',
            'tanggal_penjualan' => 'required|date',
            'biaya_operasional' => 'nullable|integer|min:0', // Ditangkap kembali saat update
        ]);

        $this->penjualanService->update($penjualan, $dataValidasi);

        return redirect()->route('penjualan')
                         ->with('success', 'Catatan penjualan berhasil diperbarui!');
    }

    /**
     * Menghapus transaksi penjualan
     */
    public function destroy(Penjualan $penjualan)
    {
        if ($penjualan->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $this->penjualanService->delete($penjualan);

        return redirect()->route('penjualan')
                         ->with('success', 'Catatan penjualan berhasil dihapus!');
    }
}