@extends('layouts.app')

@section('title', 'AgFarm - Bergabung Menjadi Mitra Tani Modern')

@section('content')
<div class="min-h-screen bg-[#F4F6F3] flex items-center justify-center font-sans antialiased overflow-x-hidden"
     x-data="registerForm()">
    <div class="max-w-7xl w-full mx-auto p-4 md:p-6 lg:p-8 grid lg:grid-cols-2 gap-8 items-stretch min-h-[85vh]">
        
        {{-- SISI KIRI: VISUAL HERO BANNER (Hidden on Mobile) --}}
        <div class="hidden lg:block relative rounded-[32px] overflow-hidden shadow-xl min-h-full">
            <img src="https://i.pinimg.com/1200x/d3/3b/67/d33b67912876f781044e6484568c6c14.jpg" 
                 class="w-full h-full object-cover object-center" alt="Petani Mitra Sukses">
            <div class="absolute inset-0 bg-gradient-to-t from-[#183B2B]/90 via-[#183B2B]/30 to-black/20"></div>
            
            <div class="absolute bottom-10 left-10 right-10 text-white space-y-3">
                <div class="inline-flex items-center gap-1.5 border border-white/20 bg-white/10 backdrop-blur-md text-white px-3 py-1 rounded-full text-[11px] font-medium shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Komunitas Lokal
                </div>
                <h2 class="text-2xl font-bold leading-snug">"Langkah awal mendigitalisasi komoditas demi ketahanan pangan nasional yang mandiri."</h2>
                <p class="text-white/70 text-xs font-light">Hikmal, 2026</p>
            </div>
        </div>

        {{-- SISI KANAN: FORM REGISTER --}}
        <div class="bg-white rounded-[32px] border border-slate-200/60 shadow-md p-8 md:p-12 flex flex-col justify-between min-w-0">
            <div>
                {{-- Logo / Header Form --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🌱</span>
                        <span class="font-bold text-lg text-[#183B2B] tracking-tight">AgFarm <span class="text-xs text-slate-400 font-normal">Join</span></span>
                    </div>
                </div>

                {{-- Salam pembuka --}}
                <div class="space-y-1 mb-6">
                    <h1 class="text-2xl font-bold text-[#183B2B] tracking-tight">Mulai Bertani Pintar</h1>
                    <p class="text-slate-400 text-xs">Buat akun mitramu dan nikmati kemudahan analisis ekosistem digital terpadu.</p>
                </div>

                {{-- 🚨 LOGIC ALERT --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-2.5">
                        <span class="text-red-500 mt-0.5 text-sm">⚠️</span>
                        <div>
                            <h5 class="text-red-800 font-bold text-xs">Pendaftaran Gagal</h5>
                            <ul class="list-disc pl-4 mt-1 text-[11px] text-red-700 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Form Elemen --}}
                <form action="/register" method="POST" class="space-y-4">
                    @csrf
                    
                    {{-- INPUT NAMA --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 tracking-wide uppercase mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">👤</span>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap Anda" 
                                class="w-full bg-[#F8FAF7] border @error('name') border-red-400 focus:border-red-400 @else border-slate-200 focus:border-[#183B2B] @enderror pl-9 pr-4 py-2.5 rounded-xl text-xs focus:outline-hidden transition-colors shadow-2xs text-slate-800">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-[10px] mt-1 font-medium">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- INPUT EMAIL --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 tracking-wide uppercase mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">📧</span>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@company.com" 
                                class="w-full bg-[#F8FAF7] border @error('email') border-red-400 focus:border-red-400 @else border-slate-200 focus:border-[#183B2B] @enderror pl-9 pr-4 py-2.5 rounded-xl text-xs focus:outline-hidden transition-colors shadow-2xs text-slate-800">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-[10px] mt-1 font-medium">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- INPUT PASSWORD (DENGAN TANDA MATA) --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 tracking-wide uppercase mb-1.5">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">🔒</span>
                            <input :type="showPassword ? 'text' : 'password'" 
                                   name="password" 
                                   x-model="password"
                                   required 
                                   placeholder="Minimal 8 karakter" 
                                   class="w-full bg-[#F8FAF7] border @error('password') border-red-400 focus:border-red-400 @else border-slate-200 focus:border-[#183B2B] @enderror pl-9 pr-10 py-2.5 rounded-xl text-xs focus:outline-hidden transition-colors shadow-2xs text-slate-800">
                            
                            {{-- Tombol Tanda Mata --}}
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-hidden">
                                <span x-show="!showPassword" class="text-sm">👁️</span>
                                <span x-show="showPassword" class="text-sm">🕶️</span>
                            </button>
                        </div>

                        {{-- INDIKATOR KEKUATAN PASSWORD DINAMIS --}}
                        <div x-show="password.length > 0" class="mt-2 space-y-1.5">
                            {{-- Progress Bar --}}
                            <div class="h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-300"
                                     :class="{
                                         'w-1/4 bg-red-400': strength === 1,
                                         'w-2/4 bg-amber-400': strength === 2,
                                         'w-3/4 bg-blue-400': strength === 3,
                                         'w-full bg-emerald-500': strength === 4
                                     }">
                                </div>
                            </div>
                            {{-- Teks Deskripsi Syarat Validasi --}}
                            <div class="grid grid-cols-2 gap-x-2 gap-y-1 text-[10px] font-medium text-slate-400">
                                <div :class="password.length >= 8 ? 'text-emerald-600 font-bold' : ''">✓ Min. 8 Karakter</div>
                                <div :class="/[A-Z]/.test(password) && /[a-z]/.test(password) ? 'text-emerald-600 font-bold' : ''">✓ Huruf Besar & Kecil</div>
                                <div :class="/[0-9]/.test(password) ? 'text-emerald-600 font-bold' : ''">✓ Memiliki Angka</div>
                                <div :class="/[^A-Za-z0-9]/.test(password) ? 'text-emerald-600 font-bold' : ''">✓ Memiliki Simbol</div>
                            </div>
                        </div>

                        @error('password')
                            <p class="text-red-500 text-[10px] mt-1 font-medium">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KETENTUAN LAYANAN --}}
                    <div class="flex items-start pt-1">
                        <input id="terms" name="terms" type="checkbox" required class="mt-0.5 h-4 w-4 rounded-sm border-slate-300 text-[#183B2B] focus:ring-[#183B2B]">
                        <label for="terms" class="ml-2 block text-[11px] text-slate-500 leading-normal">
                            Saya menyetujui <a href="#" class="text-[#183B2B] font-semibold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-[#183B2B] font-semibold hover:underline">Kebijakan Privasi</a> ekosistem AgFarm.
                        </label>
                    </div>

                    {{-- TOMBOL SUBMIT --}}
                    <button type="submit" class="w-full bg-[#183B2B] text-white font-medium py-3 rounded-xl text-xs hover:bg-[#122e21] transition shadow-xs mt-2">
                        Daftar Akun Mitra
                    </button>
                </form>
            </div>

            {{-- Footer Tautan --}}
            <div class="pt-6 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">Sudah punya akun? <a href="/login" class="text-[#183B2B] font-bold hover:underline">Masuk di Sini ↗</a></p>
            </div>
        </div>

    </div>
</div>

{{-- SCRIPT ALPINE.JS UNTUK LOGIC MATA & INDIKATOR --}}
<script>
    function registerForm() {
        return {
            password: '',
            showPassword: false,
            // Menghitung berapa syarat validasi controller yang sudah terpenuhi
            get strength() {
                let count = 0;
                if (this.password.length >= 8) count++;
                if (/[A-Z]/.test(this.password) && /[a-z]/.test(this.password)) count++;
                if (/[0-9]/.test(this.password)) count++;
                if (/[^A-Za-z0-9]/.test(this.password)) count++;
                return count;
            }
        }
    }
</script>
@endsection