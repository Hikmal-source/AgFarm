@extends('layouts.app')

@section('title', 'AgFarm - Pembukuan Penjualan')
@section('content')

{{-- Inisialisasi State Alpine.js untuk Modal Tambah & Edit Catatan Penjualan --}}
<div x-data="{ 
    isCreateModalOpen: false, 
    isEditModalOpen: false,
    editData: { id: '', panen_id: '', jumlah_terjual: '', total_pendapatan: '', tanggal_penjualan: '', biaya_operasional: 0 }
}" class="flex flex-col lg:flex-row min-h-screen max-w-full bg-[#FAFCFA] font-sans antialiased overflow-x-hidden">
    
    <x-sidebar-dashboard />

    {{-- Konten Utama --}}
    <div class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 min-w-0 overflow-y-auto pb-24 lg:pb-8">
        
        {{-- Flash Alert Notifikasi Finansial --}}
        @if(session('success'))
            <div class="p-4 text-sm text-[#183B2B] rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-2 shadow-2xs animate-fade-in">
                <span>💰</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header Menu Atas --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-[24px] border border-slate-100 shadow-2xs">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    💵 Pembukuan Kas Penjualan
                </h1>
                <p class="text-slate-400 text-xs mt-0.5">Catat hasil dagang, kelola omset kotor, dan pantau kalkulasi margin keuntungan operasional.</p>
            </div>
            <button @click="isCreateModalOpen = true" class="w-full sm:w-auto bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-3 rounded-xl text-xs font-bold shadow-sm flex items-center justify-center gap-2 transition-all transform active:scale-95">
                <span>✨</span> Catat Transaksi Baru
            </button>
        </header>

        {{-- Papan Metrik Ringkasan Atas Finansial --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">Total Omset Kotor</span>
                <span class="text-xl font-extrabold text-slate-800 mt-1 block">
                    Rp {{ number_format($daftarPenjualan->sum('total_pendapatan'), 0, ',', '.') }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">📈 Profit Bersih</span>
                <span class="text-xl font-extrabold text-emerald-700 mt-1 block">
                    Rp {{ number_format($daftarPenjualan->sum('total_profit'), 0, ',', '.') }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">📦 Volume Dagang</span>
                <span class="text-xl font-extrabold text-amber-600 mt-1 block">
                    {{ $daftarPenjualan->sum('jumlah_terjual') }} Kg
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs bg-linear-to-br from-emerald-950 to-emerald-900 text-white border-0">
                <span class="text-xs font-medium text-emerald-200 block uppercase">Frekuensi Buku</span>
                <span class="text-xl font-bold mt-1 block flex items-center gap-1.5">
                    {{ $daftarPenjualan->count() }} Invoice <span class="w-2 h-2 rounded-full bg-emerald-400 block animate-ping"></span>
                </span>
            </div>
        </div>

        {{-- Daftar Baris Penjualan (Striped List) --}}
        <div class="bg-white rounded-[28px] border border-slate-100 shadow-2xs overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-700">Histori Arus Kas Masuk</h3>
                <span class="text-[10px] bg-slate-100 text-slate-500 font-bold px-2 py-1 rounded-md">FINANSIAL</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($daftarPenjualan as $penjualan)
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/60 transition-colors">
                        
                        {{-- Kolom Info Finansial Kiri --}}
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 bg-emerald-50 text-emerald-700">
                                💰
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-800 tracking-tight">
                                    {{ $penjualan->panen->tanaman->nama_tanaman }} — <span class="text-emerald-700 font-extrabold">Rp {{ number_format($penjualan->total_pendapatan, 0, ',', '.') }}</span>
                                </h4>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-semibold text-slate-400">INV: #INV-{{ $penjualan->id }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-sm font-bold tracking-wide uppercase bg-slate-100 text-slate-700">
                                        📍 {{ $penjualan->panen->lahan->nama_lahan }}
                                    </span>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-sm font-medium bg-emerald-100 text-emerald-800 font-bold">
                                        ⚖️ Net: {{ $penjualan->jumlah_terjual }} Kg
                                    </span>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-sm font-medium bg-amber-50 text-amber-800 font-bold">
                                        ✨ Profit: Rp {{ number_format($penjualan->total_profit, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        📅 {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Aksi Kanan --}}
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100">
                            <button @click="
                                editData = { 
                                    id: '{{ $penjualan->id }}', 
                                    panen_id: '{{ $penjualan->panen_id }}', 
                                    jumlah_terjual: '{{ $penjualan->jumlah_terjual }}',
                                    total_pendapatan: '{{ $penjualan->total_pendapatan }}',
                                    tanggal_penjualan: '{{ $penjualan->tanggal_penjualan }}',
                                    biaya_operasional: '{{ $penjualan->total_pendapatan - $penjualan->total_profit }}' {{-- Hitung balik biaya --}}
                                };
                                isEditModalOpen = true;
                            " class="px-3 py-1.5 text-xs font-semibold text-amber-600 hover:bg-amber-50 rounded-lg transition-colors flex items-center gap-1">
                                🔧 Modifikasi
                            </button>

                            {{-- Form Delete Invoice --}}
                            <form action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" onsubmit="return confirm('Hapus catatan pembukuan kas penjualan ini? Tindakan audit log tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors flex items-center gap-1">
                                    🗑️ Drop
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center space-y-2">
                        <span class="text-4xl block">💸</span>
                        <h4 class="text-xs font-bold text-slate-600">Belum Ada Transaksi Penjualan</h4>
                        <p class="text-[11px] text-slate-400">Klik tombol rekam di atas untuk mencatatkan pemasukan finansial hasil tani milikmu.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <p class="text-[10px] text-slate-300 text-center pt-4">© 2026 AgFarm Ecosystem • AgroCare Financial Design</p>
    </div>

    {{-- ==================== MODAL WINDOW: BUKU PENJUALAN BARU ==================== --}}
    <div x-show="isCreateModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-transition x-cloak>
        <div @click.away="isCreateModalOpen = false" class="bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-800 flex items-center gap-1.5">💵 Catat Penjualan</h3>
                <button @click="isCreateModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xs">✕</button>
            </div>
            
            <form action="{{ route('penjualan.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sumber Hasil Panen</label>
                    <select name="panen_id" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                        <option value="">-- Pilih Batch Hasil Panen --</option>
                        @foreach($daftarPanen as $panen)
                            <option value="{{ $panen->id }}">🌾 {{ $panen->tanaman->nama_tanaman }} ({{ $panen->lahan->nama_lahan }} - {{ $panen->jumlah_panen }} Kg)</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Volume Terjual (Kg)</label>
                    <input type="number" name="jumlah_terjual" min="1" required placeholder="Contoh: 100" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Omset Pendapatan (Rp)</label>
                    <input type="number" name="total_pendapatan" min="1" required placeholder="Contoh: 2500000" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Biaya Operasional / Distribusi (Rp)</label>
                    <input type="number" name="biaya_operasional" min="0" placeholder="Contoh: 200000 (Boleh Kosong)" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_penjualan" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="isCreateModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-400 rounded-xl">Batal</button>
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL WINDOW: EDIT DATA PENJUALAN ==================== --}}
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-transition x-cloak>
        <div @click.away="isEditModalOpen = false" class="bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-800 flex items-center gap-1.5">🔧 Koreksi Pembukuan</h3>
                <button @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xs">✕</button>
            </div>
            
            <form :action="'/penjualan/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sumber Hasil Panen</label>
                    <select name="panen_id" x-model="editData.panen_id" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                        @foreach($daftarPanen as $panen)
                            <option value="{{ $panen->id }}">🌾 {{ $panen->tanaman->nama_tanaman }} ({{ $panen->lahan->nama_lahan }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Volume Terjual (Kg)</label>
                    <input type="number" name="jumlah_terjual" x-model="editData.jumlah_terjual" min="1" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Omset Pendapatan (Rp)</label>
                    <input type="number" name="total_pendapatan" x-model="editData.total_pendapatan" min="1" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Biaya Operasional / Distribusi (Rp)</label>
                    <input type="number" name="biaya_operasional" x-model="editData.biaya_operasional" min="0" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_penjualan" x-model="editData.tanggal_penjualan" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-400 rounded-xl">Batal</button>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs">Terapkan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection