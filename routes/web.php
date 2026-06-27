<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\DashboardPetaniController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\PanenController;
use App\Http\Controllers\PenjualanController;

// ==========================================
// RUTE UMUM / PUBLIC (Bisa diakses siapa saja)
// ==========================================
Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

// ==========================================
// RUTE KHUSUS GUEST (Hanya untuk yang BELUM login)
// ==========================================
Route::middleware('guest')->group(function () {
    // Registrasi
    Route::get('/register', function () {
        return view('register');
    });
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Lupa Password
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

// ==========================================
// RUTE KHUSUS AUTH (Wajib Login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // 🚪 Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==========================================
    // 📊 DASHBOARD & UPDATE FOTO PROFIL MITRA PETANI
    // ==========================================
    Route::get('/dashboard', [DashboardPetaniController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/update-photo', [DashboardPetaniController::class, 'updatePhoto'])->name('dashboard.updatePhoto');

    // 🌾 Kelola Lahan
    Route::get('/lahan', [LahanController::class, 'index'])->name('lahan');
    Route::post('/lahan', [LahanController::class, 'store'])->name('lahan.store');
    Route::put('/lahan/{lahan}', [LahanController::class, 'updateController'])->name('lahan.updateController');
    Route::delete('/lahan/{lahan}', [LahanController::class, 'destroy'])->name('lahan.destroy');

    // 🌱 Kelola Tanaman
    Route::get('/tanaman', [TanamanController::class, 'index'])->name('tanaman');
    Route::post('/tanaman', [TanamanController::class, 'store'])->name('tanaman.store');
    Route::put('/tanaman/{tanaman}', [TanamanController::class, 'update'])->name('tanaman.update');
    Route::delete('/tanaman/{tanaman}', [TanamanController::class, 'destroy'])->name('tanaman.destroy');

    // 🧺 Kelola Hasil Panen
    Route::get('/panen', [PanenController::class, 'index'])->name('panen');
    Route::post('/panen', [PanenController::class, 'store'])->name('panen.store');
    Route::put('/panen/{panen}', [PanenController::class, 'update'])->name('panen.update');
    Route::delete('/panen/{panen}', [PanenController::class, 'destroy'])->name('panen.destroy');

    // 💰 Kelola Penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');         
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');    
    Route::put('/penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.update'); 
    Route::delete('/penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy'); 

    // ==========================================
    // 👮 RUTE KHUSUS ADMIN (Hanya Bisa Diakses Jika Role === Admin)
    // ==========================================
    Route::middleware(\App\Http\Middleware\IsAdmin::class)->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/petani', [AdminController::class, 'indexPetani'])->name('admin.petani.index');
        Route::get('/admin/petani/{id}', [AdminController::class, 'showPetani'])->name('admin.petani.show');
        Route::put('/admin/petani/{id}', [AdminController::class, 'updatePetani'])->name('admin.petani.update');
        Route::delete('/admin/petani/{id}', [AdminController::class, 'destroyPetani'])->name('admin.petani.destroy');
        Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.audit-logs');
    });

});