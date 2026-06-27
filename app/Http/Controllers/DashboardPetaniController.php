<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Tanaman;
use App\Models\Panen; 
use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;

class DashboardPetaniController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        $userId = Auth::id(); 
        $totalLahan = Lahan::where('user_id', $userId)->count(); 
        $totalTanaman = Lahan::where('user_id', $userId)
                             ->whereNotNull('tanaman_id')
                             ->get()
                             ->unique('tanaman_id')
                             ->count();
        $daftarLahan = Lahan::where('user_id', $userId)->with('tanaman')->get();
        $totalPanen = Panen::where('user_id', $userId)->sum('jumlah_panen'); 
        $daftarPanen = Panen::where('user_id', $userId)->with(['tanaman', 'lahan'])->latest()->get();
        $totalPenjualan = Penjualan::where('user_id', $userId)->sum('total_profit');
        $daftarPenjualanTerbaru = Penjualan::where('user_id', $userId)
                                           ->with(['panen.tanaman'])
                                           ->latest()
                                           ->take(5)
                                           ->get();
        
        return view('dashboard', compact(
            'totalLahan', 
            'totalTanaman', 
            'totalPanen', 
            'totalPenjualan',
            'daftarLahan',
            'daftarPanen',
            'daftarPenjualanTerbaru'
        ));
    }
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            
            // Pengamanan nama file unik
            $namaFileFoto = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke direktori storage
            $file->storeAs('public/foto_profil', $namaFileFoto);

            // Update ke database lewat service
            $this->userService->updateUser($user->id, [
                'name'        => $user->name,
                'email'       => $user->email,
                'foto_profil' => $namaFileFoto
            ]);

            return back()->with('success', 'Foto profil berhasil diperbarui!');
        }

        return back()->withErrors(['foto_profil' => 'Gagal mengunggah foto.']);
    }
}