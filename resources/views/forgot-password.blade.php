@extends('layouts.app')

@section('title', 'AgFarm - Pulihkan Akses Akun Anda')

@section('content')
<div class="min-h-screen bg-[#F4F6F3] flex items-center justify-center font-sans antialiased overflow-x-hidden">
    <div class="max-w-7xl w-full mx-auto p-4 md:p-6 lg:p-8 grid lg:grid-cols-2 gap-8 items-stretch min-h-[85vh]">
        
        {{-- SISI KIRI: FORM PERMINTAAN RESET --}}
        <div class="bg-white rounded-[32px] border border-slate-200/60 shadow-md p-8 md:p-12 flex flex-col justify-between min-w-0">
            <div>
                {{-- Logo / Header Form --}}
                <div class="flex items-center gap-2 mb-8">
                    <span class="text-2xl">🌿</span>
                    <span class="font-bold text-lg text-[#183B2B] tracking-tight">AgFarm <span class="text-xs text-slate-400 font-normal">Security</span></span>
                </div>

                {{-- Salam pembuka --}}
                <div class="space-y-2 mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-[#183B2B] tracking-tight">Lupa Kata Sandi?</h1>
                    <p class="text-slate-400 text-xs md:text-sm">Jangan khawatir! Masukkan email yang terdaftar dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
                </div>

                {{-- Alert Sukses (Jika token terkirim) --}}
                @if (session('success'))
                    <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 shadow-2xs"
                         x-data="{ show: true }" x-show="show" x-transition>
                        <span class="text-emerald-600 text-sm bg-emerald-100 p-1.5 rounded-lg">📨</span>
                        <div class="flex-1">
                            <h5 class="text-[#183B2B] font-bold text-xs tracking-tight">Instruksi Terkirim</h5>
                            <p class="mt-0.5 text-[11px] text-emerald-700 leading-normal font-medium">{{ session('success') }}</p>
                        </div>
                        <button type="button" @click="show = false" class="text-emerald-400 hover:text-emerald-600 text-xs cursor-pointer">✕</button>
                    </div>
                @endif

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3 shadow-2xs"
                         x-data="{ show: true }" x-show="show" x-transition>
                        <span class="text-red-500 text-sm bg-red-100 p-1.5 rounded-lg">⚠️</span>
                        <div class="flex-1">
                            <h5 class="text-red-800 font-bold text-xs tracking-tight">Email Tidak Ditemukan</h5>
                            <p class="mt-0.5 text-[11px] text-red-700 leading-normal font-medium">{{ $errors->first('email') }}</p>
                        </div>
                        <button type="button" @click="show = false" class="text-red-400 hover:text-red-600 text-xs cursor-pointer">✕</button>
                    </div>
                @endif

                {{-- Form Elemen --}}
                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 tracking-wide uppercase mb-2">Alamat Email Terdaftar</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm">📧</span>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@company.com" 
                                class="w-full bg-[#F8FAF7] border border-slate-200 pl-10 pr-4 py-3 rounded-xl text-xs focus:outline-none focus:border-[#183B2B] transition-colors shadow-2xs text-slate-800">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#183B2B] text-white font-medium py-3 rounded-xl text-xs hover:bg-[#122e21] transition shadow-xs">
                        Kirim Tautan Pemulihan
                    </button>
                </form>
            </div>

            {{-- Footer Tautan --}}
            <div class="pt-8 border-t border-slate-100 text-center">
                <a href="/login" class="text-xs text-slate-500 hover:text-[#183B2B] font-medium transition-colors">
                    ← Kembali ke Halaman Login
                </a>
            </div>
        </div>

        {{-- SISI KANAN: VISUAL HERO BANNER --}}
        <div class="hidden lg:block relative rounded-[32px] overflow-hidden shadow-xl min-h-full">
            <img src="https://i.pinimg.com/736x/89/45/8e/89458ebd9181da6175cfb6423b13151e.jpg" 
                 class="w-full h-full object-cover" alt="AgFarm Security">
            <div class="absolute inset-0 bg-gradient-to-t from-[#183B2B]/90 via-[#183B2B]/30 to-black/20"></div>
            
            <div class="absolute bottom-10 left-10 right-10 text-white space-y-3">
                <div class="inline-flex items-center gap-1.5 border border-white/20 bg-white/10 backdrop-blur-md text-white px-3 py-1 rounded-full text-[11px] font-medium shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Proteksi Akun
                </div>
                <h2 class="text-2xl font-bold leading-snug">"Keamanan data adalah kunci dari kepercayaan di era pertanian digital."</h2>
                <p class="text-white/70 text-xs font-light">— Tim Security AgFarm</p>
            </div>
        </div>
    </div>
</div>
@endsection