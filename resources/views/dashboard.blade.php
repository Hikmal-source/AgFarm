@extends('layouts.app')

@section('title', 'AgFarm - Monitor Agrikultur Presisi')
@section('content')
{{-- Memastikan kontainer utama mengunci viewport dan menyembunyikan overflow X secara global --}}
<div class="flex flex-col lg:flex-row min-h-screen max-w-full bg-[#F8FAF7] font-sans antialiased overflow-x-hidden"
     x-data="{ openPhotoModal: false }">
    
    <x-sidebar-dashboard />

    {{-- Pembungkus konten utama + aside kanan tanpa flex-col di level ini agar layout mengalir pas --}}
    <div class="flex-1 flex flex-col lg:flex-row min-w-0 w-full">
        
        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 p-4 sm:p-6 md:p-8 space-y-5 md:space-y-6 min-w-0 overflow-y-auto">
            
            <header class="flex justify-between items-center gap-4">
                <div class="min-w-0 w-full sm:w-auto">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight truncate">Dashboard Utama</h1>
                    <p class="text-slate-400 text-[11px] sm:text-xs truncate">Selamat datang kembali di sistem pantau AgFarm🌿</p>
                </div>
                <div class="relative max-w-xs w-full hidden md:block">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">🔍</span>
                    <input type="text" placeholder="Cari blok lahan atau komoditas..." class="w-full bg-white border border-slate-200 pl-9 pr-4 py-2 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs">
                </div>
            </header>

            {{-- Row 1: Ringkasan Metrik --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 w-full">
                <x-metric-card icon="⛰️" title="Total Lahan" value="{{ $totalLahan }} Wilayah" isDark="true" />
                <x-metric-card icon="🌱" title="Jenis Tanaman" value="{{ $totalTanaman }} Varietas" />
                <x-metric-card icon="🌾" title="Total Panen" value="{{ number_format($totalPanen, 0, ',', '.') }} Kg" isDark="true" />
                <x-metric-card icon="💰" title="Penjualan" value="Rp {{ number_format($totalPenjualan, 0, ',', '.') }}" />
            </div>

            {{-- Row 2: Ringkasan Pendapatan --}}
            <div class="bg-white p-4 sm:p-5 md:p-6 rounded-[24px] sm:rounded-[28px] border border-slate-100 shadow-2xs space-y-3 sm:space-y-4 flex flex-col justify-between min-w-0">
                <div class="flex justify-between items-center gap-2">
                    <div class="min-w-0">
                        <p class="text-slate-400 text-[11px] sm:text-xs font-medium">Ringkasan Tren Pendapatan</p>
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800 truncate">
                            Rp {{ number_format($totalPenjualan, 0, ',', '.') }} 
                            @if($totalPenjualan == 0)
                                <span class="text-[10px] sm:text-xs text-slate-400 font-semibold">(Belum Ada)</span>
                            @endif
                        </h3>
                    </div>
                    <span class="flex items-center gap-1 text-[9px] sm:text-[10px] text-slate-500 font-medium shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Total Pendapatan</span>
                </div>
                
                <div class="h-36 sm:h-44 md:h-48 relative pt-2 min-w-0">
                    <canvas id="agfarmLineChart"></canvas>
                </div>
            </div>

            {{-- Row 3: Profit & Distribusi Lahan --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6 min-w-0">
                
                {{-- Kartu Khas Profit (Bar Chart) --}}
                <div class="lg:col-span-2 bg-white p-4 sm:p-5 md:p-6 rounded-[24px] sm:rounded-[28px] border border-slate-100 shadow-2xs space-y-3 sm:space-y-4 flex flex-col justify-between min-w-0">
                    <div class="flex justify-between items-center gap-2">
                        <div class="min-w-0">
                            <p class="text-slate-400 text-[11px] sm:text-xs font-medium">Analisis Keuntungan Bersih</p>
                            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800 truncate">
                                Rp {{ number_format($totalPenjualan, 0, ',', '.') }} <span class="text-[10px] sm:text-xs text-slate-400 font-semibold">(Net Profit)</span>
                            </h3>
                        </div>
                        <span class="flex items-center gap-1 text-[9px] sm:text-[10px] text-slate-500 font-medium shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Profit Bulanan</span>
                    </div>
                    
                    <div class="h-36 sm:h-44 md:h-48 relative pt-2 min-w-0">
                        <canvas id="agfarmBarChart"></canvas>
                    </div>
                </div>

                {{-- Kartu Okupansi Komoditas --}}
                <div class="bg-white p-4 sm:p-5 md:p-6 rounded-[24px] sm:rounded-[28px] border border-slate-100 shadow-2xs flex flex-col justify-between min-w-0">
                    <div>
                        <p class="text-slate-400 text-[11px] sm:text-xs font-medium">Okupansi Komoditas</p>
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Distribusi Lahan</h3>
                    </div>
                    
                    <div class="relative flex items-center justify-center my-2 h-40 sm:h-44 min-w-0">
                        <canvas id="agfarmPieChart"></canvas>
                    </div>
                </div>

            </div>
        </main>

        {{-- ASIDE RIGHT AREA --}}
        <aside class="w-full lg:w-80 xl:w-96 bg-white border-t lg:border-t-0 lg:border-l border-slate-100 p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8 flex flex-col justify-between shrink-0">
            <div class="space-y-6 sm:space-y-7">
                {{-- Bagian Profil Petani yang Login (Diberi trigger klik modal) --}}
                <div @click="openPhotoModal = true" class="flex justify-between items-center bg-[#F8FAF7] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-100 hover:border-emerald-300 transition-all cursor-pointer group shadow-3xs">
                    <div class="flex items-center gap-3 min-w-0">
                        @if(Auth::user()->foto_profil)
                            <img src="{{ asset('storage/foto_profil/' . Auth::user()->foto_profil) }}" alt="Foto {{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border border-[#183B2B] shrink-0">
                        @else
                            <div class="w-8 h-8 rounded-full bg-[#183B2B] flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 tracking-wide uppercase block group-hover:text-emerald-600 transition-colors">Petani Utama ⚙️</span>
                            <h2 class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Mitra Petani' }}</h2>
                        </div>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                {{-- SINKRON: Deteksi Otomatis Varietas Tanaman Aktif --}}
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-[11px] sm:text-xs font-bold text-slate-400 tracking-wide uppercase">Varietas Sedang Ditanam</h4>
                        <span class="text-[9px] sm:text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-sm font-bold">LIVE</span>
                    </div>
                    
                    <div class="space-y-2.5 sm:space-y-3">
                        @php $adaTanamanAktif = false; @endphp
                        
                        @isset($daftarLahan)
                            @foreach($daftarLahan as $lahanAktif)
                                @if($lahanAktif->tanaman)
                                    @php $adaTanamanAktif = true; @endphp
                                    <div class="flex items-center justify-between p-2.5 sm:p-3 bg-[#F8FAF7] rounded-xl border border-slate-100 shadow-3xs">
                                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                                            <div class="text-sm sm:text-base">
                                                {{ $lahanAktif->tanaman->kategori === 'Sayuran' ? '🥬' : '🍎' }}
                                            </div>
                                            <div class="min-w-0">
                                                <h5 class="text-xs font-bold text-slate-800 truncate">{{ $lahanAktif->tanaman->nama_tanaman }}</h5>
                                                <p class="text-[9px] sm:text-[10px] text-slate-400 truncate">📍 {{ $lahanAktif->nama_lahan }}</p>
                                            </div>
                                        </div>
                                        <span class="text-[8px] sm:text-[9px] px-1.5 py-0.5 rounded-sm font-bold uppercase shrink-0
                                            {{ $lahanAktif->tanaman->kategori === 'Sayuran' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $lahanAktif->tanaman->kategori }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        @endisset

                        @if(!$adaTanamanAktif)
                            <div class="flex flex-col items-center justify-center p-5 sm:p-6 bg-[#F8FAF7] rounded-2xl border border-dashed border-slate-200 text-center space-y-1.5">
                                <span class="text-xl sm:text-2xl">🚜</span>
                                <p class="text-xs font-bold text-slate-700">Belum Ada Tanaman</p>
                                <p class="text-[10px] text-slate-400 max-w-[180px]">Semua area lahan masih kosong. Silakan plot komoditas pangan melalui menu manajemen master data.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SINKRON: Riwayat Hasil Panen Riil dari Database --}}
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-[11px] sm:text-xs font-bold text-slate-400 tracking-wide uppercase">Riwayat Hasil Panen</h4>
                        <span class="text-[9px] sm:text-[10px] text-slate-400">Tonase (Kg)</span>
                    </div>

                    <div class="space-y-2">
                        @if(isset($daftarPanen) && $daftarPanen->count() > 0)
                            @foreach($daftarPanen->take(3) as $logPanen)
                                <div class="flex items-center justify-between p-2.5 bg-[#F8FAF7] rounded-xl border border-slate-100 shadow-3xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-xs">{{ $logPanen->tanaman->kategori === 'Sayuran' ? '🥬' : '🍎' }}</span>
                                        <div class="min-w-0">
                                            <h5 class="text-[11px] font-bold text-slate-800 truncate">{{ $logPanen->tanaman->nama_tanaman }}</h5>
                                            <p class="text-[9px] text-slate-400 truncate">⛰️ {{ $logPanen->lahan->nama_lahan }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-xs font-black text-emerald-700 block">+{{ $logPanen->jumlah_panen }} Kg</span>
                                        <span class="text-[8px] text-slate-400 block">{{ \Carbon\Carbon::parse($logPanen->tanggal_panen)->translatedFormat('d M') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center py-4 sm:py-6 text-center space-y-1">
                                <span class="text-lg sm:text-xl">📭</span>
                                <p class="text-[10px] sm:text-[11px] font-semibold text-slate-400">Belum ada riwayat aktivitas panen</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 📅 BARU: KALENDER AGRO MINI DINAMIS (Alpine.js Terintegrasi) --}}
                <div class="space-y-3 pt-2" x-data="{
                    getDaysInMonth() {
                        let now = new Date();
                        return new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
                    },
                    getCurrentDay() {
                        return new Date().getDate();
                    },
                    getStartDayOfWeek() {
                        let now = new Date();
                        return new Date(now.getFullYear(), now.getMonth(), 1).getDay();
                    }
                }">
                    <div class="flex justify-between items-center">
                        <h4 class="text-[11px] sm:text-xs font-bold text-slate-400 tracking-wide uppercase">Kalender Agro</h4>
                        <span class="text-[10px] font-bold text-[#183B2B] bg-[#F8FAF7] border border-slate-200/60 px-2.5 py-0.5 rounded-md uppercase">
                            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                        </span>
                    </div>

                    <div class="bg-[#F8FAF7] border border-slate-100 p-3 rounded-2xl shadow-3xs">
                        {{-- Header Singkatan Nama Hari --}}
                        <div class="grid grid-cols-7 gap-1 text-center text-[9px] font-bold text-slate-400 mb-2">
                            <div>MG</div><div>SN</div><div>SL</div><div>RB</div><div>KM</div><div>JM</div><div>SB</div>
                        </div>
                        
                        {{-- Grid Dinamis Tanggal --}}
                        <div class="grid grid-cols-7 gap-1 text-center text-xs">
                            {{-- Mengisi offset grid kosong sebelum tanggal 1 --}}
                            <template x-for="i in getStartDayOfWeek()">
                                <div class="py-1"></div>
                            </template>
                            
                            {{-- Loop Jumlah Hari Aktif Bulan Ini --}}
                            <template x-for="day in getDaysInMonth()">
                                <div class="py-1 font-semibold rounded-lg flex items-center justify-center text-[11px]"
                                     :class="day === getCurrentDay() ? 'bg-[#183B2B] text-white font-black shadow-xs' : 'text-slate-700 hover:bg-slate-200/50 cursor-pointer'">
                                    <span x-text="day"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            <p class="text-[10px] text-slate-300 text-center pt-4 lg:pt-6 border-t border-slate-50">© 2026 AgFarm Ecosystem • AgroCare Design</p>
        </aside>

    </div>

    {{-- ======================================================= --}}
    {{-- 🌟 POP-UP POP-UP MODAL UPDATE PROFILE & FOTO PETANI 🌟 --}}
    {{-- ======================================================= --}}
    <div x-show="openPhotoModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
         
        <div @click.away="openPhotoModal = false" 
             class="bg-white w-full max-w-sm rounded-[28px] border border-slate-100 p-6 space-y-5 shadow-xl transform transition-all">
            
            {{-- Header Pop-up --}}
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-black text-slate-800 tracking-tight">Detail Profil Pengguna</h3>
                    <p class="text-[10px] text-slate-400">Kelola identitas kartu mitra digital AgFarm</p>
                </div>
                <button @click="openPhotoModal = false" class="text-slate-400 hover:text-rose-500 text-sm p-1 transition-colors">✕</button>
            </div>

            {{-- Tampilan Foto & Nama Pengguna --}}
            <div class="flex flex-col items-center text-center p-4 bg-[#F8FAF7] rounded-2xl border border-slate-100/80 space-y-2">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/foto_profil/' . Auth::user()->foto_profil) }}" alt="Foto {{ Auth::user()->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-[#183B2B] shadow-sm">
                @else
                    <div class="w-16 h-16 rounded-full bg-[#183B2B] text-white font-black text-lg flex items-center justify-center shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="text-xs font-bold text-slate-800">{{ Auth::user()->name ?? 'Mitra Petani' }}</h4>
                    <p class="text-[10px] text-slate-400 font-medium">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-1 text-[8px] bg-emerald-100 text-emerald-800 font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">
                        {{ Auth::user()->role ?? 'Petani' }}
                    </span>
                </div>
            </div>

            {{-- Form Upload Foto Baru --}}
            <form action="{{ route('dashboard.updatePhoto') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Pilih Berkas Foto Baru</label>
                    <input type="file" name="foto_profil" accept="image/*" required
                           onchange="const file = this.files[0]; if (file && !file.type.startsWith('image/')) { alert('Peringatan Keamanan!\n\nBerkas wajib berupa gambar (JPG, JPEG, PNG, GIF).\nUpload skrip pemrograman seperti PHP, SQL, JS, atau berkas berbahaya lainnya sangat dilarang demi keamanan sistem!'); this.value = ''; }"
                           class="w-full text-xs text-slate-500 bg-white border border-slate-200 rounded-xl px-3 py-2
                                  file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] 
                                  file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 
                                  focus:outline-hidden focus:border-[#183B2B] transition-colors cursor-pointer">
                    <p class="text-[9px] text-slate-400">Format: JPG, JPEG, PNG, GIF (Maks. 2MB)</p>
                </div>

                <div class="grid grid-cols-2 gap-2.5 pt-1">
                    <button type="button" @click="openPhotoModal = false"
                            class="w-full border border-slate-200 text-slate-600 text-[11px] font-bold py-2 px-4 rounded-xl hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="w-full bg-[#183B2B] text-white text-[11px] font-bold py-2 px-4 rounded-xl hover:bg-[#122d21] transition-colors shadow-xs">
                        Simpan Foto
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- --- GENERATOR DATA GRAFIK SECARA DINAMIS (DIKIRIM KE JAVASCRIPT) --- --}}
@php
    // Inisialisasi array data 7 bulan terakhir
    $bulanLabels = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL'];
    $dataOmsetBulanan = [0, 0, 0, 0, 0, 0, 0];
    $dataProfitBulanan = [0, 0, 0, 0, 0, 0, 0];

    if (isset($daftarPenjualanTerbaru)) {
        foreach ($daftarPenjualanTerbaru as $pj) {
            // Ambil nama bulan singkatan kapital sesuai dengan array labels (Contoh: "Jan")
            $namaBulan = strtoupper(\Carbon\Carbon::parse($pj->tanggal_penjualan)->translatedFormat('M'));
            $indexBulan = array_search($namaBulan, $bulanLabels);
            
            if ($indexBulan !== false) {
                // Tambahkan akumulasi nilai penjualan ke bulan yang sesuai
                $dataProfitBulanan[$indexBulan] += $pj->total_profit;
                // Di sini diasumsikan omset kotor disamakan atau ditambahkan biaya jika kolom tersedia
                $dataOmsetBulanan[$indexBulan] += $pj->total_pendapatan; 
            }
        }
    }
@endphp
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        
        // Mengambil array data PHP hasil sinkronisasi database
        const profitDataset = {!! json_encode($dataProfitBulanan) !!};
        const omsetDataset = {!! json_encode($dataOmsetBulanan) !!};

        // --- 1. CONFIGURATION TREN PENDAPATAN (GELOMBANG HALUS / SMOOTH WAVY AREA CHART) ---
        const lineCtx = document.getElementById('agfarmLineChart').getContext('2d');
        
        // Membuat efek gradasi warna hijau yang memudar halus di bawah kurva gelombang
        const gradientGelombang = lineCtx.createLinearGradient(0, 0, 0, 200);
        gradientGelombang.addColorStop(0, '#183B2B'); // Hijau Emerald di puncak gelombang
        gradientGelombang.addColorStop(0.6, 'rgba(4, 120, 87, 0.08)');  // Hijau gelap transparan di tengah
        gradientGelombang.addColorStop(1, 'rgba(248, 250, 247, 0)');    // Benar-benar memudar di dasar

        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL'],
                datasets: [{
                    label: 'Pendapatan',
                    data: omsetDataset,
                    borderColor: '#183B2B', // Garis kurva menggunakan warna hijau emerald segar
                    borderWidth: 3,
                    fill: true, // Mengaktifkan background di bawah garis
                    backgroundColor: gradientGelombang, // Menerapkan gradasi halus yang dibuat di atas
                    tension: 0.4, // 👈 KUNCI UTAMA: Menghilangkan efek kotak & mengubahnya menjadi gelombang halus
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#183B2B',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 9 } }
                    },
                    y: {
                        grid: { color: '#f1f5f9', borderDash: [5, 5] },
                        ticks: { color: '#94a3b8', font: { size: 9 } }
                    }
                }
            }
        });

        // --- 2. CONFIGURATION ANALISIS PROFIT (BAR CHART) ---
        const barCtx = document.getElementById('agfarmBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL'],
                datasets: [{
                    label: 'Profit',
                    data: profitDataset,
                    backgroundColor: '#047857', 
                    borderRadius: 8, 
                    barThickness: window.innerWidth < 640 ? 10 : 16
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 9 } }
                    },
                    y: {
                        grid: { color: '#f1f5f9', borderDash: [5, 5] },
                        ticks: { color: '#94a3b8', font: { size: 9 } }
                    }
                }
            }
        });

        // --- 3. DIAGRAM PIE DINAMIS ---
        @php
            $dataVarietas = [];
            $lahanKosong = 0;

            if (isset($daftarLahan)) {
                foreach($daftarLahan as $l) {
                    if($l->tanaman) {
                        $namaTanaman = $l->tanaman->nama_tanaman;
                        if (isset($dataVarietas[$namaTanaman])) {
                            $dataVarietas[$namaTanaman]++;
                        } else {
                            $dataVarietas[$namaTanaman] = 1;
                        }
                    } else {
                        $lahanKosong++;
                    }
                }
            }

            $labels = array_keys($dataVarietas);
            $values = array_values($dataVarietas);

            if ($lahanKosong > 0 || empty($dataVarietas)) {
                $labels[] = 'Lahan Kosong';
                $values[] = $lahanKosong;
            }
        @endphp

        const pieCtx = document.getElementById('agfarmPieChart').getContext('2d');
        const pieLabels = {!! json_encode($labels) !!};
        const pieData = {!! json_encode($values) !!};

        const warnaKhasAgfarm = [
            '#047857', '#10b981', '#f59e0b', '#06b6d4', '#84cc16', '#a7f3d0', '#cbd5e1'
        ];

        const backgroundColors = pieLabels.map((label, index) => {
            if (label === 'Lahan Kosong') return '#cbd5e1'; 
            return warnaKhasAgfarm[index % (warnaKhasAgfarm.length - 1)];
        });

        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: backgroundColors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 8, 
                            padding: 6,  
                            font: { size: 9 }, 
                            color: '#64748b'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                
                                if (context.label === 'Lahan Kosong' && total === value) {
                                    return ' Lahan Kosong: 100% Siap Olah';
                                }
                                return ` ${context.label}: ${value} Wilayah (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection