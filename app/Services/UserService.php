<?php 

namespace App\Services;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService {
    public function getAll() {
        return User::all();
    }



    public function getById($id) {
        return User::findOrFail($id);
    }

   public function createUser($data) {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'], 
            'role'     => $data['role'] ?? 'Petani',
            'foto_profil' => $data['foto_profil'] ?? null
        ]);

        // 💡 PASANG AUDIT LOG: Saat User Berhasil Dibuat
        AuditLog::create([
            'user_id'     =>  $user->id, // ID Admin yang sedang login dan membuat user ini
            'activity'    => 'Tambah Pengguna',
            'description' => "Berhasil membuat pengguna baru: {$user->name} dengan role {$user->role}.",
            'ip_address'  => request()->ip(),
        ]);

        return $user;
    }
public function updateUser($id, array $data) {
        $user = User::findOrFail($id);
        
        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'role'  => $data['role'] ?? $user->role,
        ];
        
        if (!empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }
        if (!empty($data['foto_profil'])) {
            $updateData['foto_profil'] = $data['foto_profil'];
        }
        
        $user->update($updateData);

        AuditLog::create([
            'user_id'     => Auth::id(), // 💡 Diubah ke Auth::id()
            'activity'    => 'Ubah Pengguna',
            'description' => "Mengubah data pengguna dengan nama: {$user->name} (ID: {$user->id}).",
            'ip_address'  => request()->ip(),
        ]);

        return $user;
    }

    public function deleteUser($id) {
        $user = User::findOrFail($id);
        
        // 💡 Memperbaiki typo spasi variabel kemarin
        $namaUserYangDihapus = $user->name; 
        
        $user->delete();

        AuditLog::create([
            'user_id'     => Auth::id(), // 💡 Diubah ke Auth::id()
            'activity'    => 'Hapus Pengguna',
            'description' => "Menghapus akun pengguna bernama: {$namaUserYangDihapus} (ID: {$id}).",
            'ip_address'  => request()->ip(),
        ]);

        return true;
    }


    public function sendResetToken($email)
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return false;
        }

        $token = Str::random(60);

        // Simpan atau update token ke database bawaan Laravel (password_reset_tokens)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token), // Token di-hash demi keamanan database
                'created_at' => now()
            ]
        );

        // 💡 PASANG AUDIT LOG: Permintaan Reset Password
        AuditLog::create([
            'user_id'     => $user->id,
            'activity'    => 'Minta Reset Password',
            'description' => "Pengguna dengan email {$email} meminta token pengaturan ulang kata sandi.",
            'ip_address'  => request()->ip(),
        ]);

        // Return token murni (plaintext) untuk nanti dikirim ke email atau disimulasikan
        return $token;
    }
    public function resetPasswordWithToken($email, $token, $newPassword)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return 'email_not_found';
        }
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record || !Hash::check($token, $record->token)) {
            return 'invalid_token';
        }
        $user->update([
            'password' => $newPassword
        ]);
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        AuditLog::create([
            'user_id'     => $user->id,
            'activity'    => 'Reset Password Berhasil',
            'description' => "Kata sandi untuk email {$email} berhasil diperbarui melalui token valid.",
            'ip_address'  => request()->ip(),
        ]);

        return true;
    }
}