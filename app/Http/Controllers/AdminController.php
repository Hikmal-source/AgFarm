<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Lahan;
use App\Models\Panen;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Halaman Dashboard Utama Admin
    public function index()
    {
        $totalPetani = User::where('role', 'Petani')->count();
        $totalLahan = Lahan::query()->count();
        $totalPanenNasional = Panen::sum('jumlah_panen');
        $totalProfitSistem = Penjualan::sum('total_profit');
        $daftarLahanTerkini = Lahan::with(['user', 'tanaman'])->latest()->take(5)->get();

        // Mengambil semua daftar mitra petani untuk dimanajemen langsung di dashboard
        $semuaPetani = User::where('role', 'Petani')->latest()->paginate(10);

        // Hitung pendaftaran mitra petani bulanan secara dinamis (Tahun berjalan 2026)
        $chartLabels = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL'];
        $chartData = [
            User::where('role', 'Petani')->whereMonth('created_at', 1)->count(),
            User::where('role', 'Petani')->whereMonth('created_at', 2)->count(),
            User::where('role', 'Petani')->whereMonth('created_at', 3)->count(),
            User::where('role', 'Petani')->whereMonth('created_at', 4)->count(),
            User::where('role', 'Petani')->whereMonth('created_at', 5)->count(),
            User::where('role', 'Petani')->whereMonth('created_at', 6)->count(),
            User::where('role', 'Petani')->whereMonth('created_at', 7)->count(),
        ];

        // Hitung penambahan khusus bulan ini saja untuk teks di sub-header chart
        $petaniBulanIni = User::where('role', 'Petani')->whereMonth('created_at', now()->month)->whereYear('created_at', 2026)->count();

        return view('admin.dashboard', compact(
            'totalPetani', 
            'totalLahan', 
            'totalPanenNasional', 
            'totalProfitSistem',
            'daftarLahanTerkini',
            'semuaPetani',
            'chartLabels',   
            'chartData',     
            'petaniBulanIni' 
        ));
    }

    // ==========================================
    // ➕ BARU: Halaman Utama Khusus Manajemen Kemitraan Petani (admin.user)
    // ==========================================
    public function indexPetani()
    {
        // 1. Ambil list petani untuk halaman grid card (9 per halaman)
        $semuaPetani = User::where('role', 'Petani')->latest()->paginate(9);

        // 2. Data statistik untuk komponen Aside sebelah kanan
        $totalPetani = User::where('role', 'Petani')->count();
        $petaniBulanIni = User::where('role', 'Petani')
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', 2026)
                            ->count();

        return view('admin.petani', compact('semuaPetani', 'totalPetani', 'petaniBulanIni'));
    }

    // Detail Profil & Analisis Statistik Riil Per Petani (Menghitung Lahan, Sayur, Panen, & Pendapatan)
    public function showPetani($id)
    {
        // Ambil data petani spesifik yang berrole Petani beserta relasi lahannya
        $petani = User::where('role', 'Petani')->with(['lahan.tanaman'])->findOrFail($id);

        // 1. Menghitung Berapa Lahan yang dimiliki
        $totalLahan = $petani->lahan->count();
        // 2. Menghitung Jenis Sayuran/Komoditas unik yang ditanam
        $totalSayuran = $petani->lahan->pluck('tanaman_id')->filter()->unique()->count();
        
        // 3. Menghitung Akumulasi Total Berat Hasil Panen (Kg) dari seluruh lahan miliknya
        $totalPanen = Panen::whereIn('lahan_id', $petani->lahan->pluck('id'))->sum('jumlah_panen');
        
        // 4. Menghitung Total Pendapatan / Nilai Penjualan Petani
        $totalPendapatan = Penjualan::where('user_id', $petani->id)->sum('total_pendapatan');

        // Ambil history aktivitas/Audit Log khusus petani ini untuk ditaruh di komponen bawah
        $aktivitasPetani = AuditLog::where('user_id', $petani->id)->latest()->take(10)->get();

        // Ambil data agregat nominal transaksi per bulan untuk visualisasi Chart penjualan
        $chartLabels = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN'];
        $chartData = [
            Penjualan::where('user_id', $petani->id)->whereMonth('created_at', 1)->sum('total_pendapatan') / 1000000,
            Penjualan::where('user_id', $petani->id)->whereMonth('created_at', 2)->sum('total_pendapatan') / 1000000,
            Penjualan::where('user_id', $petani->id)->whereMonth('created_at', 3)->sum('total_pendapatan') / 1000000,
            Penjualan::where('user_id', $petani->id)->whereMonth('created_at', 4)->sum('total_pendapatan') / 1000000,
            Penjualan::where('user_id', $petani->id)->whereMonth('created_at', 5)->sum('total_pendapatan') / 1000000,
            Penjualan::where('user_id', $petani->id)->whereMonth('created_at', 6)->sum('total_pendapatan') / 1000000,
        ];

        return view('admin.petani', compact(
            'petani',
            'totalLahan',
            'totalSayuran',
            'totalPanen',
            'totalPendapatan',
            'aktivitasPetani',
            'chartLabels',
            'chartData'
        ));
    }

    // Update Profil, Kredensial, atau Reset Password Petani oleh Admin
    public function updatePetani(Request $request, $id)
    {
        $petani = User::where('role', 'Petani')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $petani->id,
            'password' => 'nullable|string|min:8',
        ]);

        $oldName = $petani->name;
        $oldEmail = $petani->email;

        $petani->name = $request->name;
        $petani->email = $request->email;

        // Jika form password diisi, enkripsi dan perbarui password petani
        if ($request->filled('password')) {
            $petani->password = bcrypt($request->password);
        }

        $petani->save();

        // Log audit perubahan internal data
        AuditLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'Update Data Petani',
            'description' => "Admin memperbarui data milik '{$oldName}' ({$oldEmail}) menjadi Nama: {$petani->name}, Email: {$petani->email}.",
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Data profil mitra petani berhasil diperbarui secara aman.');
    }

    // Fungsi Eksekusi Mutlak / Mengeluarkan Petani yang Melanggar dari Sistem
    public function destroyPetani($id)
    {
        $petani = User::where('role', 'Petani')->findOrFail($id);
        
        // Catat di Audit Log sebelum data dihapus dari database
        AuditLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'Hapus Mitra Petani',
            'description' => "Administrator mengeksekusi penghapusan mutlak akun mitra bernama: {$petani->name} ({$petani->email}).",
            'ip_address'  => request()->ip(),
        ]);

        $petani->delete();

        // Diarahkan kembali ke halaman index petani dengan flash data sukses
        return redirect()->route('admin.petani.index')->with('success', 'Mitra petani berhasil didepak dan dihapus dari sistem ekosistem AgFarm.');
    }

    // Halaman Khusus Audit Log (Tetap aman tidak diganggu)
    public function auditLogs()
    {
        $auditLogs = AuditLog::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.audit-logs', compact('auditLogs'));
    }
}