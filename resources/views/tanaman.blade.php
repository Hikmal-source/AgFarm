@extends('layouts.app')

@section('title', 'AgFarm - Katalog Varietas Tanaman')
@section('content')

{{-- Inisialisasi State Alpine.js untuk Modal Tambah & Edit Tanaman --}}
<div x-data="{ 
    isCreateModalOpen: false, 
    isEditModalOpen: false,
    editData: { id: '', nama_tanaman: '', kategori: '', lahan_id: '' }
}" class="flex min-h-screen max-w-full bg-[#FAFCFA] font-sans antialiased overflow-x-hidden">
    
    <x-sidebar-dashboard />

    {{-- Konten Utama (Full Width tanpa Side Panel Kanan) --}}
    <div class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 min-w-0 overflow-y-auto">
        
        {{-- Flash Alert Notifikasi --}}
        @if(session('success'))
            <div class="p-4 text-sm text-[#183B2B] rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-2 shadow-2xs animate-fade-in">
                <span>🥕</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header Menu Atas --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-[24px] border border-slate-100 shadow-2xs">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    🌱 Master Komoditas Pangan
                </h1>
                <p class="text-slate-400 text-xs mt-0.5">Daftar varietas tanaman global yang didukung oleh ekosistem AgFarm.</p>
            </div>
            <button @click="isCreateModalOpen = true" class="w-full sm:w-auto bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-3 rounded-xl text-xs font-bold shadow-sm flex items-center justify-center gap-2 transition-all transform active:scale-95">
                <span>✨</span> Daftarkan Tanaman Baru
            </button>
        </header>

        {{-- Papan Metrik Ringkasan Atas --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">Total Varietas</span>
                <span class="text-xl font-extrabold text-slate-800 mt-1 block">{{ $daftarTanaman->count() }} Jenis</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">🥬 Kategori Sayur</span>
                <span class="text-xl font-extrabold text-emerald-700 mt-1 block">
                    {{ $daftarTanaman->where('kategori', 'Sayuran')->count() }} Item
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">🍎 Kategori Buah</span>
                <span class="text-xl font-extrabold text-amber-600 mt-1 block">
                    {{ $daftarTanaman->where('kategori', 'Buah-buahan')->count() }} Item
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs bg-linear-to-br from-emerald-950 to-emerald-900 text-white border-0">
                <span class="text-xs font-medium text-emerald-200 block uppercase">Status Katalog</span>
                <span class="text-xl font-bold mt-1 block flex items-center gap-1.5">
                    Aktif <span class="w-2 h-2 rounded-full bg-emerald-400 block animate-ping"></span>
                </span>
            </div>
        </div>

        {{-- Daftar Baris Tanaman (Striped List) --}}
        <div class="bg-white rounded-[28px] border border-slate-100 shadow-2xs overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-700">Katalog Data Riil</h3>
                <span class="text-[10px] bg-slate-100 text-slate-500 font-bold px-2 py-1 rounded-md">AUTO-SYNC</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($daftarTanaman as $tanaman)
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/60 transition-colors">
                        
                        {{-- Kolom Info Kiri --}}
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0
                                {{ $tanaman->kategori === 'Sayuran' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $tanaman->kategori === 'Sayuran' ? '🥬' : '🍎' }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-800 tracking-tight">{{ $tanaman->nama_tanaman }}</h4>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-semibold text-slate-400">ID: #{{ $tanaman->id }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-sm font-bold tracking-wide uppercase
                                        {{ $tanaman->kategori === 'Sayuran' ? 'bg-emerald-100/70 text-emerald-800' : 'bg-amber-100/70 text-amber-800' }}">
                                        {{ $tanaman->kategori }}
                                    </span>
                                    @php
                                        $lahanUserIni = $tanaman->lahan->where('user_id', Auth::id());
                                    @endphp

                                    @if($lahanUserIni->count() > 0)
                                        @foreach($lahanUserIni as $lahanDitempati)
                                            <span class="text-[10px] bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded-sm font-medium">
                                                📍 Area: {{ $lahanDitempati->nama_lahan }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-[10px] bg-rose-50 text-rose-600 px-1.5 py-0.5 rounded-sm font-medium">
                                            ⚠️ Belum diplot ke lahan manapun
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Aksi Kanan --}}
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100">
                            <button @click="
                                editData = { 
                                    id: '{{ $tanaman->id }}', 
                                    nama_tanaman: '{{ $tanaman->nama_tanaman }}', 
                                    kategori: '{{ $tanaman->kategori }}',
                                    lahan_id: '{{ $lahanUserIni->first()->id ?? '' }}'
                                };
                                isEditModalOpen = true;
                            " class="px-3 py-1.5 text-xs font-semibold text-amber-600 hover:bg-amber-50 rounded-lg transition-colors flex items-center gap-1">
                                🔧 Modifikasi
                            </button>

                            {{-- Form Delete Tanaman --}}
                            <form action="{{ route('tanaman.destroy', $tanaman->id) }}" method="POST" onsubmit="return confirm('Menghapus varietas ini akan mengosongkan status komoditas pada lahan yang menanamnya, Lanjutkan?');">
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
                        <span class="text-4xl block">🍃</span>
                        <h4 class="text-xs font-bold text-slate-600">Katalog Tanaman Kosong</h4>
                        <p class="text-[11px] text-slate-400">Belum ada komoditas pangan yang terdaftar di database utama lahanmu.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <p class="text-[10px] text-slate-300 text-center pt-4">© 2026 AgFarm Ecosystem • AgroCare Design</p>
    </div>
    {{-- ==================== MODAL WINDOW: TAMBAH TANAMAN ==================== --}}
    <div x-show="isCreateModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-transition x-cloak>
        <div @click.away="isCreateModalOpen = false" class="bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-800 flex items-center gap-1.5">🌱 Daftarkan Varietas</h3>
                <button @click="isCreateModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xs">✕</button>
            </div>
            
            <form action="{{ route('tanaman.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Komoditas Tanaman</label>
                    <input type="text" name="nama_tanaman" required placeholder="Contoh: Sawi Sendok, Jeruk Purut" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori Klaster</label>
                    <select name="kategori" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                        <option value="Sayuran">🥬 Sayuran</option>
                        <option value="Buah-buahan">🍎 Buah-buahan</option>
                    </select>
                </div>

                {{-- Dropdown Pilihan Lahan (Sudah disaring di controller berdasarkan milik sendiri) --}}
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Langsung Plot ke Lahan (Opsional)</label>
                    <select name="lahan_id" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                        <option value="">-- Simpan ke Katalog Saja (Gak Ditanam) --</option>
                        @isset($daftarLahan)
                            @foreach($daftarLahan as $lahan)
                                <option value="{{ $lahan->id }}">⛰️ {{ $lahan->nama_lahan }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="isCreateModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-400 rounded-xl">Batal</button>
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL WINDOW: EDIT TANAMAN ==================== --}}
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-transition x-cloak>
        <div @click.away="isEditModalOpen = false" class="bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-800 flex items-center gap-1.5">🔧 Edit Varietas</h3>
                <button @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xs">✕</button>
            </div>
            
            <form :action="'/tanaman/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Komoditas Tanaman</label>
                    <input type="text" name="nama_tanaman" x-model="editData.nama_tanaman" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori Klaster</label>
                    <select name="kategori" x-model="editData.kategori" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                        <option value="Sayuran">🥬 Sayuran</option>
                        <option value="Buah-buahan">🍎 Buah-buahan</option>
                    </select>
                </div>

                {{-- Dropdown Pindah Lahan pada saat update --}}
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pindahkan / Tanam di Lahan</label>
                    <select name="lahan_id" x-model="editData.lahan_id" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                        <option value="">-- Lepas dari Lahan Manapun --</option>
                        @isset($daftarLahan)
                            @foreach($daftarLahan as $lahan)
                                <option value="{{ $lahan->id }}">⛰️ {{ $lahan->nama_lahan }}</option>
                            @endforeach
                        @endisset
                    </select>
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