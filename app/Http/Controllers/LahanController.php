<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tanaman;
use App\Models\Lahan;
use App\Services\LahanService;
use App\Http\Controllers\Controller;


class LahanController extends Controller
{
    protected $lahanservice;

    public function __construct(LahanService $lahanService) 
    {
        $this->lahanservice = $lahanService;
    }

    public function index() {
        $lahans = $this->lahanservice->getAll();

        $tanaman = Tanaman::all();
        return view('lahan', compact('lahans', 'tanaman'));
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'nama_lahan'  => 'required|string|max:255',
            'lokasi_blok' => 'required|string|max:255',
            'tanaman_id'  => 'nullable|exists:tanaman,id'
        ]);
        
        $this->lahanservice->create($validated);
        return redirect()->route('lahan')->with('success', 'Lahan baru berhasil di daftarkan');
    }

    public function updateController(Request $request, Lahan $lahan)
    {
        $validated = $request->validate([
            'nama_lahan'  => 'required|string|max:255',
            'lokasi_blok' => 'required|string|max:255',
            'tanaman_id'  => 'nullable|exists:tanaman,id',
        ]);
        $this->lahanservice->update($lahan, $validated);

        return redirect()->route('lahan')->with('success', 'Data lahan berhasil diperbarui!');
    }

    public function destroy(Lahan $lahan)
    {
        $this->lahanservice->delete($lahan);
        return redirect()->route('lahan')->with('success', 'Lahan berhasil dihapus dari sistem!');
    }


}
