@extends('layouts.app')

@section('title', 'Tentang AgFarm - Melangkah Bersama Teknologi Pertanian Modern')

@section('content')

<section class="relative h-[60vh] flex items-center bg-[#183B2B] text-white px-6 md:px-16 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1920&q=80" 
             class="w-full h-full object-cover object-center transform scale-105 opacity-40" alt="AgFarm Kebun Hijau">
        <div class="absolute inset-0 bg-gradient-to-b from-[#183B2B]/80 via-[#183B2B]/40 to-[#F4F6F3]"></div>
    </div>

    <div class="max-w-7xl mx-auto w-full relative z-10 pt-20">
        <div class="max-w-3xl space-y-4">
            <div class="inline-flex items-center gap-1.5 border border-white/20 bg-white/10 backdrop-blur-xs text-white px-3 py-1 rounded-full text-[11px] font-medium shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Kenali Kami Lebih Dekat
            </div>
            <h1 class="text-4xl md:text-6xl font-semibold tracking-tight leading-tight drop-shadow-xs">
                Mendigitalkan Sektor <br><span class="text-emerald-400">Agrikultur</span> Nusantara
            </h1>
            <p class="text-white/80 text-sm md:text-base max-w-xl font-normal leading-relaxed">
                AgFarm lahir dari kegelisahan atas panjangnya rantai pasok dan belum meratanya teknologi presisi di tangan para pahlawan pangan lokal.
            </p>
        </div>
    </div>
</section>

<section class="bg-[#F4F6F3] py-16 px-6 md:px-16 relative z-10 -mt-12">
    <div class="max-w-7xl mx-auto grid md:grid-cols-12 gap-12 items-center bg-white p-8 md:p-12 rounded-[32px] border border-slate-200/60 shadow-sm">
        
        <div class="md:col-span-5 relative">
            <div class="w-full h-[400px] rounded-2xl overflow-hidden shadow-md">
                <img src="https://i.pinimg.com/736x/a2/85/83/a28583ab971988a183da98a988b5690d.jpg" 
                     class="w-full h-full object-cover" alt="Pendiri AgFarm Berdiskusi dengan Petani">
            </div>
            <div class="absolute -bottom-6 -right-4 bg-[#1C3F2D] text-white p-5 rounded-2xl shadow-xl border border-white/10 max-w-[200px] hidden md:block">
                <p class="text-2xl font-bold text-emerald-400">2021</p>
                <p class="text-[10px] text-white/80 leading-tight mt-1">Berdedikasi penuh merestorasi ekosistem pangan hulu ke hilir.</p>
            </div>
        </div>

        <div class="md:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-1.5 border border-slate-200 bg-[#F4F6F3] text-slate-600 px-3 py-1 rounded-full text-[11px] font-medium shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span> Cerita Kami
            </div>
            <h3 class="text-2xl md:text-4xl font-semibold text-[#183B2B] tracking-tight">
                Menjembatani Tradisi Petani Menuju Era Otomatisasi
            </h3>
            <p class="text-slate-600 text-sm leading-relaxed">
                Kami percaya bahwa kearifan lokal para petani jika dikombinasikan dengan kecerdasan buatan (*AI*) dan sensor berbasis internet (*IoT*) mampu menciptakan ketahanan pangan yang luar biasa kokoh. AgFarm tidak hadir untuk menggantikan peran manusia, melainkan melengkapi mereka dengan data akurat agar terhindar dari kerugian akibat cuaca ekstrim dan permainan harga tengkulak.
            </p>
            
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div>
                    <h5 class="font-bold text-[#183B2B] text-sm">Teknologi Inklusif</h5>
                    <p class="text-slate-400 text-xs mt-1">Dibuat sesederhana mungkin agar mudah digunakan oleh petani lintas generasi.</p>
                </div>
                <div>
                    <h5 class="font-bold text-[#183B2B] text-sm">Dampak Berkelanjutan</h5>
                    <p class="text-slate-400 text-xs mt-1">Fokus pada penghematan penggunaan air harian dan minimalisasi limbah kimia tanah.</p>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection