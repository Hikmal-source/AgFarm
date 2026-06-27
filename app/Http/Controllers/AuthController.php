<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter; 
use Illuminate\Support\Str;                 
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException; 

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $dataSelesai = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ];

        $user = $this->userService->createUser($dataSelesai);

        return redirect('/login')->with('success', 'Akun AgFarm Pembeli berhasil dibuat. Selamat bergabung');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 1. 👈 MEMBUAT KUNCI PEMBATASAN (Berdasarkan Email & IP Address)
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        // 2. 👈 JIKA SUDAH MELEBIHI 3 KALI PERCOBAAN, AKUN DIBEKUKAN
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            throw ValidationException::withMessages([
                'email' => ["Terlalu banyak percobaan login. Akses Anda dibekukan sementara. Silakan coba lagi dalam {$minutes} menit."],
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, true)) {
            // 3. 👈 LOGIN BERHASIL: Hapus riwayat percobaan gagal (Clear Limiter)
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            $userAktif = Auth::user();

            // Audit Log: Login Berhasil
            AuditLog::create([
                'user_id'     => $userAktif->id,
                'activity'    => 'Login Berhasil',
                'description' => 'Pengguna dengan email ' . $request->email . ' berhasil masuk ke sistem.',
                'ip_address'  => $request->ip()
            ]);

            // SEKAT HALAMAN (Membagi rute berdasarkan Role pengguna)
            if ($userAktif->role === 'Admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin!');
            } elseif ($userAktif->role === 'Petani') {
                return redirect('/dashboard')->with('success', 'Selamat bekerja kembali!');
            }

            return redirect('/')->with('success', 'Login successful');
        }

        // 4. 👈 LOGIN GAGAL: Tambah hitungan percobaan (Hit Rate Limiter) selama 15 menit (900 detik)
        RateLimiter::hit($throttleKey, 900);

        // Memperbaiki struktur pencarian user untuk audit log agar standar Laravel
        $user = User::where('email', $request->email)->first();

        // Audit Log: Gagal Login
        AuditLog::create([
            'user_id'     => $user ? $user->id : null, 
            'activity'    => 'Gagal Login',
            'description' => 'Percobaan login gagal menggunakan email: ' . $request->email,
            'ip_address'  => $request->ip()
        ]);

        return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        if (Auth::check()) {
            AuditLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Logout',
                'description' => 'Pengguna dengan email ' . Auth::user()->email . ' telah keluar dari sistem.',
                'ip_address'  => $request->ip()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Logout successful');
    }

    public function forgotPassword() {
        return view('forgot-password');
    }

    public function sendResetLink(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Alamat email tidak terdaftar dalam sistem kami.'
        ]);

        // Panggil fungsi buatan kita di UserService
        $token = $this->userService->sendResetToken($request->email);

        if (!$token) {
            return back()->withErrors(['email' => 'Gagal memproses permintaan reset.']);
        }

        // 💡 CATATAN SIMULASI: 
        // Karena di lokal, kita langsung alihkan ke halaman input password baru 
        // sambil membawa tokennya lewat session (pengganti kirim email asli)
        return redirect()->route('password.reset', ['token' => $token, 'email' => $request->email])
                         ->with('success', 'Token reset password berhasil dibuat untuk simulasi!');
    }

    public function resetPassword(Request $request, $token) {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Eksekusi Update Password ke Database
     */
    public function updatePassword(Request $request) {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => [
                'required',
                'string',
                'confirmed', // Wajib ada field password_confirmation di blade
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        // Jalankan logika reset via UserService
        $status = $this->userService->resetPasswordWithToken(
            $request->email, 
            $request->token, 
            $request->password
        );

        if ($status === true) {
            return redirect('/login')->with('success', 'Kata sandi berhasil diperbarui! Silakan masuk kembali.');
        }

        return redirect()->route('password.request')->withErrors([
            'email' => 'Token kedaluwarsa atau permintaan tidak valid. Silakan ulangi proses.'
        ]);
    }
    
}