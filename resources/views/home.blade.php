@extends('layouts.app')

@section('title', 'AgFarm - Pertanian Pintar untuk Masa Depan Berkelanjutan')

@section('content')

<section class="relative min-h-screen flex items-center bg-[#183B2B] text-white px-6 md:px-16 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1622383563227-04401ab4e5ea?auto=format&fit=crop&w=1920&q=80" 
             class="w-full h-full object-cover object-center transform scale-105" alt="AgFarm Latar Belakang Pertanian">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/10 to-black/30"></div>
    </div>

    <div class="max-w-7xl mx-auto w-full relative z-10 grid md:grid-cols-12 gap-8 items-center pt-24 pb-12">
        <div class="md:col-span-8 space-y-6">
            <h1 class="text-4xl md:text-7xl font-semibold tracking-tight leading-[1.05] drop-shadow-sm">
                Pertanian Pintar untuk <br> Masa Depan Hijau
            </h1>
            <p class="text-white/90 text-sm md:text-base max-w-md font-normal leading-relaxed drop-shadow-xs">
                Kami membantu petani meningkatkan produktivitas hasil panen dan keberlanjutan lingkungan melalui inovasi teknologi agrikultur terpadu.
            </p>
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="#" class="bg-white text-[#183B2B] font-medium px-6 py-3 rounded-full text-xs hover:bg-slate-100 transition shadow-sm">
                    Jelajahi Solusi
                </a>
                <a href="#" class="border border-white/40 text-white backdrop-blur-xs font-medium px-6 py-3 rounded-full text-xs hover:bg-white/20 transition">
                    Konsultasi Gratis
                </a>
            </div>
        </div>

        <div class="md:col-span-4 flex md:justify-end items-end h-full pt-12 md:pt-0">
            <div class="bg-white/90 backdrop-blur-md p-3 rounded-2xl flex items-center gap-3 w-full max-w-[270px] shadow-2xl border border-white/40 transform md:translate-y-24">
                <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=100&q=80" 
                     class="w-12 h-12 object-cover rounded-xl shadow-inner" alt="Pelatihan Tani">
                <div class="flex-1 min-w-0">
                    <h4 class="text-slate-800 font-bold text-xs truncate">Pelatihan Tani Online</h4>
                    <p class="text-slate-500 text-[10px] mb-1">48rb+ Peserta Bergabung</p>
                    <div class="flex items-center justify-between gap-1">
                        <span class="text-[9px] text-[#2E7D32] bg-[#E8F5E9] px-2 py-0.5 rounded-full font-semibold">Akses Gratis</span>
                        <a href="#" class="text-[10px] bg-[#1C3F2D] text-white px-2.5 py-1 rounded-full font-medium hover:bg-black transition flex items-center shadow-xs">Ikuti <span class="ml-0.5 text-[8px]">↗</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#F4F6F3] py-16 px-6 relative z-20">
    <div class="max-w-4xl mx-auto flex flex-col items-start md:pl-16 pt-8">
        <div class="inline-flex items-center gap-1.5 border border-slate-200 bg-white text-slate-600 px-3 py-1 rounded-full text-[11px] font-medium mb-4 shadow-2xs">
            <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span> Tentang Kami
        </div>
        <h2 class="text-xl md:text-3xl font-medium text-[#183B2B] leading-snug text-left max-w-3xl">
            Sistem AgFarm Membantu Petani <span class="inline-flex items-center">🧑‍🌾</span> Mengoptimalkan Pengelolaan Lahan Serta Mempercepat Proses Distribusi <span class="inline-flex items-center">🚜</span> Penjualan Hasil Panen Secara Digital.
        </h2>
    </div>
</section>

<section class="bg-[#F4F6F3] pb-16 px-6 md:px-16">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-end mb-8">
            <div>
                <div class="inline-flex items-center gap-1.5 border border-slate-200 bg-white text-slate-600 px-3 py-1 rounded-full text-[11px] font-medium mb-3 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span> Solusi Kami
                </div>
                <h3 class="text-2xl md:text-4xl font-semibold text-[#183B2B]">Inovasi untuk Sektor Pertanian</h3>
            </div>
            <a href="#" class="border border-slate-300 text-slate-700 bg-white px-4 py-1.5 rounded-full text-xs font-medium hover:bg-slate-50 transition shadow-2xs">
                Lihat Semua
            </a>
        </div>

        <div class="space-y-4" x-data="{ activeIndex: 2 }">
            
            <div class="bg-white rounded-[24px] p-5 border transition-all duration-300 ease-in-out"
                 :class="activeIndex === 1 ? 'border-[#1C3F2D] shadow-md' : 'border-slate-200/60 shadow-2xs'">
                
                <div class="flex justify-between items-center cursor-pointer select-none" @click="activeIndex = activeIndex === 1 ? 0 : 1">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm transition-colors duration-300"
                             :class="activeIndex === 1 ? 'bg-[#1C3F2D] text-white' : 'bg-[#E8F5E9]'">💧</div>
                        <div>
                            <h4 class="font-bold text-sm md:text-base transition-colors duration-300"
                                :class="activeIndex === 1 ? 'text-[#1C3F2D]' : 'text-slate-800'">Irigasi Otomatis Berbasis IoT</h4>
                            <p class="text-slate-400 text-xs mt-0.5">Pemberian air presisi demi menjaga kelembapan tanah tanpa manipulasi manual yang boros.</p>
                        </div>
                    </div>
                    <span class="text-slate-400 text-xs font-light px-2 transition-transform duration-300 ease-in-out" 
                          :class="activeIndex === 1 ? 'rotate-45 text-[#1C3F2D] font-bold' : ''">↗</span>
                </div>
                
                <div x-show="activeIndex === 1" x-collapse x-cloak>
                    <div class="pt-5 mt-4 border-t border-slate-100 grid md:grid-cols-12 gap-5 items-center">
                        <div class="md:col-span-3">
                            <div class="w-full h-32 rounded-xl overflow-hidden shadow-xs transform transition-transform duration-500 hover:scale-105">
                                <img src="https://images.unsplash.com/photo-1563514227147-6d2ff665a6a0?w=600&auto=format&fit=crop&q=60" 
                                     class="w-full h-full object-cover" alt="Sistem Irigasi Pintar">
                            </div>
                        </div>
                        <div class="md:col-span-9">
                            <p class="text-slate-500 text-xs leading-relaxed font-normal">
                                Sistem manajemen pengairan otomatis terintegrasi menggunakan sensor kelembapan tanah (*soil moisture sensor*) dan mikrokontroler berbasis IoT untuk membaca parameter cekaman air tanaman secara *real-time*. Air hanya akan dialirkan ketika sensor mendeteksi tingkat hidrasi tanah berada di bawah ambang batas minimum varietas tanaman. Pendekatan mikro-irigasi tetes ini terbukti mampu menekan koefisien pemborosan evaporasi air hingga 45%, mempertahankan porositas hara makro tanah, serta memastikan distribusi kelembapan yang homogen di sekitar perakaran vegetatif tanaman demi pertumbuhan yang optimal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-5 border transition-all duration-300 ease-in-out"
                 :class="activeIndex === 2 ? 'border-[#1C3F2D] shadow-md' : 'border-slate-200/60 shadow-2xs'">
                
                <div class="flex justify-between items-center cursor-pointer select-none" @click="activeIndex = activeIndex === 2 ? 0 : 2">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm transition-colors duration-300"
                             :class="activeIndex === 2 ? 'bg-[#1C3F2D] text-white' : 'bg-[#E8F5E9]'">🚜</div>
                        <div>
                            <h4 class="font-bold text-sm md:text-base transition-colors duration-300"
                                :class="activeIndex === 2 ? 'text-[#1C3F2D]' : 'text-slate-800'">Metode Pemetaan Lahan Modern</h4>
                            <p class="text-slate-400 text-xs mt-0.5">Teknik analisis data geografis untuk mengukur kesuburan tanah perkebunan.</p>
                        </div>
                    </div>
                    <span class="text-slate-400 text-xs font-light px-2 transition-transform duration-300 ease-in-out" 
                          :class="activeIndex === 2 ? 'rotate-45 text-[#1C3F2D] font-bold' : ''">↗</span>
                </div>
                
                <div x-show="activeIndex === 2" x-collapse x-cloak>
                    <div class="pt-5 mt-4 border-t border-slate-100 grid md:grid-cols-12 gap-5 items-center">
                        <div class="md:col-span-3">
                            <div class="w-full h-32 rounded-xl overflow-hidden shadow-xs transform transition-transform duration-500 hover:scale-105">
                                <img src="https://images.unsplash.com/photo-1586771107445-d3ca888129ff?w=600&auto=format&fit=crop&q=60" 
                                     class="w-full h-full object-cover" alt="Dasbor Pertanian Digital">
                            </div>
                        </div>
                        <div class="md:col-span-9">
                            <p class="text-slate-500 text-xs leading-relaxed font-normal">
                                Pengelolaan pertanian presisi (*precision agriculture*) memanfaatkan wahana nirawak (*drone*) berkamera multispektral guna memetakan indeks vegetasi (NDVI) dan variabilitas topografi area penanaman. Data spasial yang dihimpun kemudian diproses oleh kecerdasan buatan (*AI*) untuk mendeteksi area zona kritis hara atau sebaran defisiensi nitrogen. Melalui pemetaan digital interaktif ini, petani dapat mengaplikasikan dosis pupuk NPK dan pestisida secara spesifik dan terukur hanya pada klaster komoditas yang membutuhkan (*variable rate application*), mencegah akumulasi racun kimia di dalam tanah, sekaligus memangkas pengeluaran modal operasional pembibitan hulu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-5 border transition-all duration-300 ease-in-out"
                 :class="activeIndex === 3 ? 'border-[#1C3F2D] shadow-md' : 'border-slate-200/60 shadow-2xs'">
                
                <div class="flex justify-between items-center cursor-pointer select-none" @click="activeIndex = activeIndex === 3 ? 0 : 3">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm transition-colors duration-300"
                             :class="activeIndex === 3 ? 'bg-[#1C3F2D] text-white' : 'bg-[#E8F5E9]'">🌾</div>
                        <div>
                            <h4 class="font-bold text-sm md:text-base transition-colors duration-300"
                                :class="activeIndex === 3 ? 'text-[#1C3F2D]' : 'text-slate-800'">Manajemen Pengeringan Hasil Panen</h4>
                            <p class="text-slate-400 text-xs mt-0.5">Menjaga kualitas komoditas pangan pasca-panen agar harga jual tetap stabil tinggi.</p>
                        </div>
                    </div>
                    <span class="text-slate-400 text-xs font-light px-2 transition-transform duration-300 ease-in-out" 
                          :class="activeIndex === 3 ? 'rotate-45 text-[#1C3F2D] font-bold' : ''">↗</span>
                </div>
                
                <div x-show="activeIndex === 3" x-collapse x-cloak>
                    <div class="pt-5 mt-4 border-t border-slate-100 grid md:grid-cols-12 gap-5 items-center">
                        <div class="md:col-span-3">
                            <div class="w-full h-32 rounded-xl overflow-hidden shadow-xs transform transition-transform duration-500 hover:scale-105">
                                <img src="https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=600&auto=format&fit=crop&q=60" 
                                     class="w-full h-full object-cover" alt="Pengeringan Gabah Pasca Panen">
                            </div>
                        </div>
                        <div class="md:col-span-9">
                            <p class="text-slate-500 text-xs leading-relaxed font-normal">
                                Tahap pasca-panen memegang peranan krusial dalam menentukan nilai jual akhir sebuah produk tani di rantai pasokan logistik komoditas nasional. AgFarm menyediakan sistem otomatisasi pemantauan suhu serta kelembapan relatif (*RH*) udara berbasis kubah kontrol termal sirkulasi. Teknologi ini menjaga kadar air internal biji-bijian, seperti gabah atau jagung, berada pada tingkat kering ideal berkisar 13-14% guna mencegah pembusukan biologis, aktivitas bakteri, ataupun infeksi jamur aflatoksin yang merusak kualitas fisik pangan, memastikan hasil panen lolos standar kurasi pabrik pengolahan pangan utama dengan penawaran harga terbaik.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg-[#F4F6F3] py-16 px-6 md:px-16">
    <div class="max-w-7xl mx-auto space-y-12">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center gap-1.5 border border-slate-200 bg-white text-slate-600 px-3 py-1 rounded-full text-[11px] font-medium shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span> Layanan Utama
            </div>
            <h3 class="text-2xl md:text-4xl font-semibold text-[#183B2B]">Fokus Ekosistem AgFarm</h3>
            <p class="text-slate-500 text-xs max-w-xl mx-auto">Komitmen penuh kami dalam memberdayakan petani lokal melalui pemanfaatan platform agrikultur digital hulu ke hilir.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-2xs space-y-4">
                <div class="w-12 h-12 bg-[#E8F5E9] text-[#166E4E] rounded-xl flex items-center justify-center text-xl font-bold">🌱</div>
                <h4 class="font-bold text-base text-[#183B2B]">Manajemen Lahan</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Optimasi struktur hara tanah dan pemetaan zonasi area penanaman bibit unggul yang disesuaikan dengan kondisi geografis wilayah Anda.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-2xs space-y-4">
                <div class="w-12 h-12 bg-[#E8F5E9] text-[#166E4E] rounded-xl flex items-center justify-center text-xl font-bold">🤝</div>
                <h4 class="font-bold text-base text-[#183B2B]">Sistem Estimasi Panen</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Prediksi jadwal dan kuantitas bobot hasil panen menggunakan algoritma cerdas untuk mengurangi risiko kerugian gagal tanam.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-2xs space-y-4">
                <div class="w-12 h-12 bg-[#E8F5E9] text-[#166E4E] rounded-xl flex items-center justify-center text-xl font-bold">📦</div>
                <h4 class="font-bold text-base text-[#183B2B]">Jaringan Penjualan</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Menghubungkan langsung tengkulak atau petani mandiri ke pasar grosir komoditas utama demi memotong rantai pasok yang tidak sehat.</p>
            </div>
        </div>

        <div class="relative w-full h-[350px] md:h-[480px] rounded-[32px] overflow-hidden shadow-lg mt-12">
            <img src="https://images.unsplash.com/photo-1595855759920-86582396756a?auto=format&fit=crop&w=1200&q=80" 
                 class="w-full h-full object-cover object-center" alt="Petani menanam padi">
            
            <div class="absolute bottom-6 left-6 right-6 md:right-auto bg-black/40 backdrop-blur-md text-white p-6 rounded-2xl max-w-xl border border-white/20 space-y-4">
                <span class="bg-[#2E7D32] text-white text-[10px] px-2.5 py-0.5 rounded-full font-medium">Pencapaian Kinerja</span>
                <h4 class="text-lg md:text-xl font-bold">Dampak Nyata, Hasil Panen Melimpah</h4>
                <p class="text-white/80 text-xs leading-relaxed">Lewat ketekunan, teknologi modern, dan kepedulian tinggi terhadap kelestarian alam, kami sukses membangun komunitas pertanian digital modern yang mandiri finansial.</p>
                
                <div class="grid grid-cols-3 gap-4 pt-2 border-t border-white/20 text-center">
                    <div>
                        <p class="text-base font-bold text-emerald-400">3.2 Juta</p>
                        <p class="text-[9px] text-white/70">Ton Hasil Didistribusi</p>
                    </div>
                    <div>
                        <p class="text-base font-bold text-emerald-400">Rp 75M+</p>
                        <p class="text-[9px] text-white/70">Perputaran Ekonomi</p>
                    </div>
                    <div>
                        <p class="text-base font-bold text-emerald-400">250+</p>
                        <p class="text-[9px] text-white/70">Kelompok Tani Binaan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection