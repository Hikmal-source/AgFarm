@if(!Request::is('dashboard', 'dashboard/*', 'login', 'register', 'admin', 'admin/*', 'lahan', 'tanaman', 'panen', 'penjualan', 'forgot-password', 'reset-password', 'reset-password/*'))

<nav class="absolute top-0 left-0 w-full z-50 bg-transparent" x-data="{ mobileMenu: false }">
    <div class="max-w-7xl mx-auto px-6 md:px-16 h-24 flex justify-between items-center">
        <div class="text-2xl font-bold text-white flex items-center gap-2 cursor-pointer drop-shadow-sm">
            AgFarm
        </div>
        
        <div class="hidden md:flex items-center gap-8 text-xs font-medium text-white/90 drop-shadow-xs">
            <a href="{{ url('/') }}" class="{{ Request::is('/') ? 'text-white font-bold' : 'hover:text-white/70' }} transition">Home</a>
            <a href="/about" class="{{ Request::is('about') ? 'text-white font-bold' : 'hover:text-white/70' }} transition">About Us</a>
            <a href="/contact" class="hover:text-white/70 transition">Contact</a>
        </div>

        {{-- 🖥️ MENU KANAN (DESKTOP) --}}
        <div class="hidden md:flex items-center gap-4">
            @guest
                {{-- Muncul kalau belum login --}}
                <a href="/login" class="border border-white/30 bg-white/10 text-white backdrop-blur-xs px-6 py-2.5 rounded-full text-xs font-medium hover:bg-white hover:text-[#183B2B] transition-all shadow-sm">
                    Masuk Akun
                </a>
                <a href="/register" class="bg-white text-[#183B2B] px-6 py-2.5 rounded-full text-xs font-bold hover:bg-slate-100 transition-all shadow-sm">
                    Daftar Mitra
                </a>
            @endguest

            @auth
                {{-- Muncul kalau sudah login --}}
                <a href="/dashboard" class="border border-white/30 bg-white/10 text-white backdrop-blur-xs px-5 py-2.5 rounded-full text-xs font-medium hover:bg-white hover:text-[#183B2B] transition-all shadow-sm">
                    Dashboard ↗
                </a>
                
                {{-- Tombol Logout Desktop (Wajib Pakai Form POST) --}}
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-full text-xs font-medium transition-all shadow-sm cursor-pointer">
                        Keluar
                    </button>
                </form>
            @endauth
        </div>

        <button @click="mobileMenu = !mobileMenu" class="md:hidden text-white focus:outline-none">
            <svg x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
            <svg x-show="mobileMenu" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- 📱 MENU RESPONSIVE (MOBILE) --}}
    <div x-show="mobileMenu" x-cloak x-transition class="md:hidden bg-white/95 backdrop-blur-md border-b border-slate-100 p-6 space-y-4 shadow-xl">
        <a href="{{ url('/') }}" class="block font-medium text-[#183B2B]">Home</a>
        <a href="/about" class="block font-medium text-slate-600">About Us</a>
        <a href="/contact" class="block font-medium text-slate-600">Contact</a>
        <hr class="border-slate-100">
        <div class="flex flex-col gap-2">
            @guest
                <a href="/login" class="text-center py-2.5 text-xs font-semibold text-[#183B2B] border border-[#183B2B] rounded-full">Masuk</a>
                <a href="/register" class="text-center py-2.5 text-xs font-semibold text-white bg-[#183B2B] rounded-full shadow-sm">Daftar Mitra</a>
            @endguest
            
            @auth
                <a href="/dashboard" class="text-center py-2.5 text-xs font-semibold text-white bg-[#183B2B] rounded-full shadow-sm">Buka Dashboard ↗</a>
                
                {{-- Tombol Logout Mobile --}}
                <form action="/logout" method="POST" class="w-full flex flex-col">
                    @csrf
                    <button type="submit" class="text-center py-2.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-full shadow-sm cursor-pointer">
                        Keluar Akun
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

@endif