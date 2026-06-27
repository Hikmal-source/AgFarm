@extends('layouts.app')

@section('title', 'AgFarm - Buat Kata Sandi Baru')

@section('content')
<div class="min-h-screen bg-[#F4F6F3] flex items-center justify-center font-sans antialiased overflow-x-hidden"
     x-data="resetForm()">
    <div class="max-w-7xl w-full mx-auto p-4 md:p-6 lg:p-8 grid lg:grid-cols-2 gap-8 items-stretch min-h-[85vh]">
        
        {{-- SISI KIRI: FORM INPUT PASSWORD BARU --}}
        <div class="bg-white rounded-[32px] border border-slate-200/60 shadow-md p-8 md:p-12 flex flex-col justify-between min-w-0">
            <div>
                {{-- Logo --}}
                <div class="flex items-center gap-2 mb-8">
                    <span class="text-2xl">🔐</span>
                    <span class="font-bold text-lg text-[#183B2B] tracking-tight">AgFarm <span class="text-xs text-slate-400 font-normal">Update</span></span>
                </div>

                {{-- Salam pembuka --}}
                <div class="space-y-2 mb-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-[#183B2B] tracking-tight">Buat Sandi Baru</h1>
                    <p class="text-slate-400 text-xs md:text-sm">Silakan buat kata sandi baru yang kuat untuk mengamankan akun ekosistem pertanian Anda.</p>
                </div>

                {{-- 🚨 PENTING: ALERT ERROR VALIDASI (Muncul jika password tidak memenuhi kriteria) --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3 shadow-2xs"
                         x-data="{ show: true }" x-show="show" x-transition>
                        <span class="text-red-500 text-sm bg-red-100 p-1.5 rounded-lg">⚠️</span>
                        <div class="flex-1">
                            <h5 class="text-red-800 font-bold text-xs tracking-tight">Gagal Memperbarui</h5>
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
                <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                    @csrf
                    {{-- Pastikan nilai token dan email aman terjaga di request --}}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ request()->email ?? $email }}">

                    {{-- PASSWORD BARU --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 tracking-wide uppercase mb-2">Kata Sandi Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm">🔒</span>
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                                class="w-full bg-[#F8FAF7] border border-slate-200 pl-10 pr-10 py-3 rounded-xl text-xs focus:outline-none focus:border-[#183B2B] transition-colors shadow-2xs text-slate-800">
                            
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 cursor-pointer">
                                <span x-show="!showPassword">👁️</span>
                                <span x-show="showPassword">🕶️</span>
                            </button>
                        </div>

                        {{-- Indikator Kekuatan --}}
                        <div x-show="password.length > 0" class="mt-2 space-y-1.5">
                            <div class="h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-300"
                                     :class="{
                                         'w-1/4 bg-red-400': strength === 1,
                                         'w-2/4 bg-amber-400': strength === 2,
                                         'w-3/4 bg-blue-400': strength === 3,
                                         'w-full bg-emerald-500': strength === 4
                                     }"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-x-2 gap-y-1 text-[9px] font-medium text-slate-400 uppercase">
                                <div :class="password.length >= 8 ? 'text-emerald-600 font-bold' : ''">✓ Min. 8 Karakter</div>
                                <div :class="/[A-Z]/.test(password) && /[a-z]/.test(password) ? 'text-emerald-600 font-bold' : ''">✓ Huruf Besar/Kecil</div>
                            </div>
                        </div>
                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 tracking-wide uppercase mb-2">Konfirmasi Sandi Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm">🛡️</span>
                            <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" 
                                class="w-full bg-[#F8FAF7] border border-slate-200 pl-10 pr-4 py-3 rounded-xl text-xs focus:outline-none focus:border-[#183B2B] transition-colors shadow-2xs text-slate-800">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#183B2B] text-white font-medium py-3 rounded-xl text-xs hover:bg-[#122e21] transition shadow-xs mt-2 cursor-pointer">
                        Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>

        {{-- SISI KANAN: VISUAL HERO BANNER --}}
        <div class="hidden lg:block relative rounded-[32px] overflow-hidden shadow-xl min-h-full">
            <img src="https://i.pinimg.com/736x/11/ba/f6/11baf6c33a7078af8800a7a545bc218f.jpg" 
                 class="w-full h-full object-cover" alt="AgFarm Reset">
            <div class="absolute inset-0 bg-gradient-to-t from-[#183B2B]/90 via-[#183B2B]/30 to-black/20"></div>
            
            <div class="absolute bottom-10 left-10 right-10 text-white space-y-3">
                <div class="inline-flex items-center gap-1.5 border border-white/20 bg-white/10 backdrop-blur-md text-white px-3 py-1 rounded-full text-[11px] font-medium shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Verifikasi Identitas
                </div>
                <h2 class="text-2xl font-bold leading-snug">"Pondasi pertanian masa depan dibangun di atas keamanan teknologi yang kokoh."</h2>
                <p class="text-white/70 text-xs font-light">— AgFarm Dev Team</p>
            </div>
        </div>
    </div>
</div>

<script>
    function resetForm() {
        return {
            password: '',
            showPassword: false,
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