<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Tanaman;
use App\Services\TanamanService;
use App\Models\Lahan;
use Illuminate\Support\Facades\Auth;

class TanamanController extends Controller
{
    protected $tanamanService;

    public function __construct(TanamanService $tanamanService)
    {
        $this->tanamanService = $tanamanService;
    }

    public function index()
    {
        $userId = Auth::id();
        $semuaTanaman = $this->tanamanService->getAll();
        $daftarTanaman = $semuaTanaman->filter(function ($tanaman) use ($userId) {
            return $tanaman->lahan->contains('user_id', $userId);
        });
        $daftarLahan = Lahan::where('user_id', $userId)->get();
        
        return view('tanaman', compact('daftarTanaman', 'daftarLahan'));
    }
    public function store(Request $request)
    {
        $dataValidasi = $request->validate([
            'nama_tanaman' => 'required|string|max:255',
            'kategori'     => 'required|in:Sayuran,Buah-buahan',
        ]);

        $tanaman = $this->tanamanService->create($dataValidasi);

        if ($request->has('lahan_id') && $request->lahan_id != null) {
            Lahan::where('id', $request->lahan_id)->where('user_id', Auth::id())->update([
                'tanaman_id' => $tanaman->id
            ]);
        }

        return redirect()->route('tanaman')
                         ->with('success', 'Master data tanaman berhasil ditambahkan!');
    }
    public function update(Request $request, Tanaman $tanaman)
    {
        $dataValidasi = $request->validate([
            'nama_tanaman' => 'required|string|max:255',
            'kategori'     => 'required|in:Sayuran,Buah-buahan',
        ]);

        // Eksekusi pembaruan data + Audit Log lewat Service
        $this->tanamanService->update($tanaman, $dataValidasi);
        Lahan::where('tanaman_id', $tanaman->id)->update(['tanaman_id' => null]);

        if ($request->has('lahan_id') && $request->lahan_id != null) {
            Lahan::where('id', $request->lahan_id)->where('user_id', Auth::id())->update([
                'tanaman_id' => $tanaman->id
            ]);
        }

        return redirect()->route('tanaman')
                         ->with('success', 'Data tanaman berhasil diperbarui!');
    }

    public function destroy(Tanaman $tanaman)
    {
        // Eksekusi penghapusan data + Audit Log lewat Service
        $this->tanamanService->delete($tanaman);
        Lahan::where('tanaman_id', $tanaman->id)->where('user_id', Auth::id())->update(['tanaman_id' => null]);

        return redirect()->route('tanaman')
                         ->with('success', 'Master data tanaman berhasil dihapus!');
    }
}
