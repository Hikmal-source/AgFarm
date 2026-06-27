@extends('layouts.app')

@section('title', 'AgFarm - Panel Kendali Admin')

@section('content')

<div class="flex flex-col lg:flex-row min-h-screen max-w-full bg-[#F8FAF7] font-sans antialiased overflow-x-hidden">
    
    <x-admin.sidebar-dashboard />

    {{-- Layout diratakan penuh tanpa pembatas Aside kanan --}}
    <div class="flex-1 flex flex-col min-w-0 w-full">
        
        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 min-w-0 overflow-y-auto">
            
            <header class="flex justify-between items-center gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight truncate">HQ Administrator</h1>
                    <p class="text-slate-400 text-xs truncate">Sistem kendali ekosistem AgFarm 🛡️</p>
                </div>
                <div class="relative max-w-xs w-full hidden md:block">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">🔍</span>
                    <input type="text" placeholder="Cari nama petani atau ID wilayah..." class="w-full bg-white border border-slate-200 pl-9 pr-4 py-2 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs">
                </div>
            </header>

            {{-- Row 1: Ringkasan Metrik Admin --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full">
                <x-metric-card icon="👥" title="Total Mitra Petani" value="{{ $totalPetani }} User" isDark="true" />
                <x-metric-card icon="🗺️" title="Total Lahan Nasional" value="{{ $totalLahan }} Lahan" />
                <x-metric-card icon="🔄" title="Perputaran Dana (Profit)" value="Rp {{ number_format($totalProfitSistem, 0, ',', '.') }}" isDark="true" />
            </div>

            {{-- Row 2: Pertumbuhan Pengguna (SINKRON DATA NYATA) --}}
            <div class="bg-white p-5 md:p-6 rounded-[28px] border border-slate-100 shadow-2xs space-y-4 flex flex-col justify-between min-w-0">
                <div class="flex justify-between items-center gap-2">
                    <div>
                        <p class="text-slate-400 text-xs font-medium">Tren Pertumbuhan Mitra</p>
                        <h3 class="text-xl md:text-2xl font-bold text-slate-800">
                            +{{ $petaniBulanIni }} Petani <span class="text-xs text-emerald-600 font-semibold">(Bulan Ini)</span>
                        </h3>
                    </div>
                    <span class="flex items-center gap-1 text-[10px] text-slate-500 font-medium shrink-0"><span class="w-2 h-2 rounded-full bg-[#183B2B]"></span> Registrasi Mitra</span>
                </div>
                <div class="h-48 relative pt-2 min-w-0">
                    <canvas id="adminUserChart"></canvas>
                </div>
            </div>

            {{-- Row 3: Aktivitas Mitra & Kalender Agenda --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 min-w-0">
                {{-- 🌾 SINKRON: Tabel Monitoring Lahan Petani Real-time --}}
                <div class="lg:col-span-2 bg-white p-5 md:p-6 rounded-[28px] border border-slate-100 shadow-2xs space-y-4 flex flex-col min-w-0">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-slate-400 text-xs font-medium">Monitoring Real-time</p>
                            <h3 class="text-lg font-bold text-slate-800">Aktivitas Lahan Mitra</h3>
                        </div>
                        <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full">Sistem Aktif</span>
                    </div>
                    
                    <div class="overflow-x-auto min-w-full">
                        <table class="min-w-full text-xs text-left text-slate-600">
                            <thead class="text-[10px] uppercase text-slate-400 border-b border-slate-100">
                                <tr>
                                    <th class="py-2 font-bold">Mitra Petani</th>
                                    <th class="py-2 font-bold">Komoditas / Tanaman</th>
                                    <th class="py-2 font-bold text-right">Status Lahan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($daftarLahanTerkini as $lahan)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3">
                                            <div class="font-semibold text-slate-800">{{ $lahan->user->name ?? 'Petani Anonim' }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $lahan->lokasi ?? 'Lokasi belum diatur' }}</div>
                                        </td>
                                        <td class="py-3 text-slate-600 font-medium">
                                            {{ $lahan->tanaman->nama_tanaman ?? 'Belum Ditanami' }}
                                        </td>
                                        <td class="py-3 text-right">
                                            @if($lahan->tanaman_id)
                                                <span class="bg-amber-50 text-amber-700 px-2 py-1 rounded-md text-[10px] font-bold">Masa Tanam</span>
                                            @else
                                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-[10px] font-bold">Lahan Kosong</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-center text-slate-400 italic">Belum ada aktivitas lahan terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 📅 Komponen Kalender Kegiatan AgFarm --}}
                <div class="bg-white p-5 md:p-6 rounded-[28px] border border-slate-100 shadow-2xs flex flex-col justify-between min-w-0">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-slate-400 text-xs font-medium">Jadwal Operasional</p>
                                <h3 id="calendarMonthYear" class="text-base font-bold text-slate-800 tracking-tight">Bulan Jadwal</h3>
                            </div>
                            <span class="text-[10px] bg-emerald-50 text-emerald-800 font-bold px-2 py-0.5 rounded-sm">Hari Ini</span>
                        </div>

                        {{-- Struktur Grid Kalender --}}
                        <div class="w-full text-center">
                            {{-- Nama Hari --}}
                            <div class="grid grid-cols-7 gap-1 text-[10px] font-bold text-slate-400 mb-2">
                                <div>M</div><div>S</div><div>S</div><div>R</div><div>K</div><div>J</div><div>S</div>
                            </div>
                            {{-- Wadah Angka Tanggal (Diisi via JS) --}}
                            <div id="calendarDays" class="grid grid-cols-7 gap-1 text-xs font-semibold text-slate-700">
                                {{-- Tanggal masuk otomatis --}}
                            </div>
                        </div>
                    </div>

                    {{-- Mini Indikator Agenda Hari Ini di Bawah Kalender --}}
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2">
                        <div class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></div>
                        <p class="text-[10px] text-slate-500 font-medium truncate">Total hasil panen terkumpul: {{ number_format($totalPanenNasional, 0, ',', '.') }} Kg</p>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="text-[10px] text-slate-300 text-center py-4 bg-white border-t border-slate-50">
            © 2026 AgFarm Core • Admin Management Panel
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        
        // --- 1. SINKRON: LINE CHART ADMIN (DATA NYATA DARI DATABASE) ---
        const adminLineCtx = document.getElementById('adminUserChart').getContext('2d');
        const gradientUser = adminLineCtx.createLinearGradient(0, 0, 0, 180);
        gradientUser.addColorStop(0, 'rgba(24, 59, 43, 0.2)');
        gradientUser.addColorStop(1, 'rgba(24, 59, 43, 0.0)');

        new Chart(adminLineCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels), // <-- Sinkronisasi Label Bulan
                datasets: [{
                    data: @json($chartData),   // <-- Sinkronisasi Nilai Angka Riil
                    borderColor: '#183B2B',
                    borderWidth: 3,
                    backgroundColor: gradientUser,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,            // Diubah jadi 4 agar titik koordinat terlihat saat disorot mouse
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 9 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 9 } } }
                }
            }
        });

        // --- 2. JAVASCRIPT AUTOMATIC MINI CALENDAR ---
        const calendarDays = document.getElementById("calendarDays");
        const calendarMonthYear = document.getElementById("calendarMonthYear");

        const date = new Date();
        const currentYear = date.getFullYear();
        const currentMonth = date.getMonth();
        const today = date.getDate();

        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        calendarMonthYear.innerText = `${monthNames[currentMonth]} ${currentYear}`;

        const firstDayIndex = new Date(currentYear, currentMonth, 1).getDay();
        const lastDay = new Date(currentYear, currentMonth + 1, 0).getDate();

        let daysHtml = "";

        for (let i = 0; i < firstDayIndex; i++) {
            daysHtml += `<div class="py-1 text-transparent">.</div>`;
        }

        for (let i = 1; i <= lastDay; i++) {
            if (i === today) {
                daysHtml += `<div class="py-1 bg-[#183B2B] text-white rounded-lg font-bold flex items-center justify-center shadow-xs">${i}</div>`;
            } else {
                daysHtml += `<div class="py-1 text-slate-700 hover:bg-slate-100 rounded-md cursor-pointer transition-colors flex items-center justify-center">${i}</div>`;
            }
        }

        calendarDays.innerHTML = daysHtml;
    });
</script>
@endsection