@extends('layouts.app')

@section('title', 'AgFarm - Masuk ke Ekosistem Pertanian Pintar')

@section('content')
<div class="min-h-screen bg-[#F4F6F3] flex items-center justify-center font-sans antialiased overflow-x-hidden">
    <div class="max-w-7xl w-full mx-auto p-4 md:p-6 lg:p-8 grid lg:grid-cols-2 gap-8 items-stretch min-h-[85vh]">
        
        {{-- SISI KIRI: FORM LOGIN (Ditambahkan Alpine.js x-data untuk mendeteksi status freeze) --}}
        <div class="bg-white rounded-[32px] border border-slate-200/60 shadow-md p-8 md:p-12 flex flex-col justify-between min-w-0 relative overflow-hidden"
             x-data="{ isFrozen: '{{ $errors->first('email') }}'.includes('dibekukan') }">
            
            {{-- 🧊 OVERLAY LAYAR FREEZE (Hanya muncul jika terkena Rate Limit) --}}
            <div x-show="isFrozen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute inset-0 bg-white/80 backdrop-blur-md z-50 flex flex-col items-center justify-center p-6 text-center">
                
                <div class="w-16 h-16 bg-red-50 border border-red-100 rounded-2xl flex items-center justify-center text-2xl shadow-2xs mb-4 animate-bounce">
                    ❄️
                </div>
                
                <h3 class="text-lg font-bold text-red-900 tracking-tight">Akses Masuk Dikunci Sementara</h3>
                
                <p class="mt-2 text-xs text-slate-600 max-w-xs leading-relaxed font-medium">
                    {{ $errors->first('email') }}
                </p>
                
                <div class="mt-5 inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-semibold px-3 py-1.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Sistem Mengunci Otomatis (15 Menit)
                </div>
            </div>

            <div>
                {{-- Logo / Header Form --}}
                <div class="flex items-center gap-2 mb-8">
                    <span class="text-2xl">🌿</span>
                    <span class="font-bold text-lg text-[#183B2B] tracking-tight">AgFarm <span class="text-xs text-slate-400 font-normal">Ecosystem</span></span>
                </div>

                {{-- Salam pembuka --}}
                <div class="space-y-2 mb-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-[#183B2B] tracking-tight">Selamat Datang Kembali!</h1>
                    <p class="text-slate-400 text-xs md:text-sm">Silakan masuk untuk memantau perkembangan lahan dan mengelola hasil panenmu hari ini</p>
                </div>

                {{-- 🌿 1. ALERT SUKSES --}}

                {{-- 🚨 2. ALERT ERROR VALIDASI BIASA (Hanya muncul jika BUKAN error freeze) --}}
                @if ($errors->any() && !str_contains($errors->first('email'), 'dibekukan'))
                    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3 shadow-2xs"
                         x-data="{ show: true }" x-show="show" x-transition>
                        <span class="text-red-500 text-sm bg-red-100 p-1.5 rounded-lg">⚠️</span>
                        <div class="flex-1">
                            <h5 class="text-red-800 font-bold text-xs tracking-tight">Gagal Masuk</h5>
                            <ul class="list-disc pl-4 mt-0.5 text-[11px] text-red-700 space-y-0.5 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" @click="show = false" class="text-red-400 hover:text-red-600 text-xs cursor-pointer">✕</button>
                    </div>
                @endif

                {{-- Form Elemen --}}
                <form action="/login" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 tracking-wide uppercase mb-2">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm">📧</span>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@company.com" 
                                class="w-full bg-[#F8FAF7] border @error('email') border-red-400 focus:border-red-400 @else border-slate-200 focus:border-[#183B2B] @enderror pl-10 pr-4 py-3 rounded-xl text-xs focus:outline-hidden transition-colors shadow-2xs text-slate-800">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-semibold text-slate-700 tracking-wide uppercase">Kata Sandi</label>
                            <a href="/forgot-password" class="text-[11px] text-[#183B2B] font-semibold hover:underline">Lupa Sandi?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm">🔒</span>
                            <input type="password" name="password" required placeholder="••••••••" 
                                class="w-full bg-[#F8FAF7] border border-slate-200 pl-10 pr-4 py-3 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs text-slate-800">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded-sm border-slate-300 text-[#183B2B] focus:ring-[#183B2B]">
                        <label for="remember_me" class="ml-2 block text-xs text-slate-500 selection:bg-transparent cursor-pointer">Ingat perangkat saya</label>
                    </div>

                    <button type="submit" class="w-full bg-[#183B2B] text-white font-medium py-3 rounded-xl text-xs hover:bg-[#122e21] transition shadow-xs mt-2">
                        Login
                    </button>
                </form>
            </div>

            {{-- Footer Tautan --}}
            <div class="pt-8 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">Belum punya akun mitra? <a href="/register" class="text-[#183B2B] font-bold hover:underline">Daftar Sekarang ↗</a></p>
            </div>
        </div>

        {{-- SISI KANAN: VISUAL HERO BANNER (Hidden on Mobile) --}}
        <div class="hidden lg:block relative rounded-[32px] overflow-hidden shadow-xl min-h-full">
            <img src="https://i.pinimg.com/1200x/d1/6d/36/d16d36633a72e607bb083e3a9f73e3a5.jpg" 
                 class="w-full h-full object-cover" alt="AgFarm Pertanian Presisi">
            <div class="absolute inset-0 bg-gradient-to-t from-[#183B2B]/90 via-[#183B2B]/30 to-black/20"></div>
            
            <div class="absolute bottom-10 left-10 right-10 text-white space-y-3">
                <div class="inline-flex items-center gap-1.5 border border-white/20 bg-white/10 backdrop-blur-md text-white px-3 py-1 rounded-full text-[11px] font-medium shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Presisi Real-Time
                </div>
                <h2 class="text-2xl font-bold leading-snug">"Teknologi membantu kita bekerja selaras dengan alam, bukan mendiktenya."</h2>
                <p class="text-white/70 text-xs font-light">— Hikmal, 2026</p>
            </div>
        </div>

    </div>
</div>
@endsection