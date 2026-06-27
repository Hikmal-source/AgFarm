@extends('layouts.app')

@section('title', 'AgFarm - Manajemen Lahan Petani')
@section('content')

{{-- Inisialisasi State Alpine.js untuk Modal Tambah & Edit di Level Atas --}}
<div x-data="{ 
    isCreateModalOpen: false, 
    isEditModalOpen: false,
    editData: { id: '', nama_lahan: '', lokasi_blok: '', tanaman_id: '' }
}" class="flex flex-col lg:flex-row min-h-screen max-w-full bg-[#F8FAF7] font-sans antialiased overflow-x-hidden">
    
    <x-sidebar-dashboard />

    {{-- Pembungkus Konten Utama --}}
    <div class="flex-1 flex flex-col lg:flex-row min-w-0 w-full">
        
        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 min-w-0 overflow-y-auto">
            
            {{-- Flash Alert Notifikasi Sukses --}}
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-2 shadow-2xs">
                    <span>🌿</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Header Menu --}}
            <header class="flex justify-between items-center gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight truncate">Manajemen Lahan</h1>
                    <p class="text-slate-400 text-xs truncate">Kelola wilayah kebun dan komoditas tanaman aktif kamu di sini⛰️</p>
                </div>
                {{-- Tombol Pemicu Modal Tambah Lahan --}}
                <button @click="isCreateModalOpen = true" class="bg-[#183B2B] hover:bg-[#23533d] text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-xs flex items-center gap-2 transition-all">
                    <span>➕</span> Tambah Lahan
                </button>
            </header>

            {{-- Grid Kartu Informasi Lahan Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 w-full">
                @forelse($lahans as $lahan)
                    <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-2xs flex flex-col justify-between space-y-4 hover:shadow-xs transition-shadow relative overflow-hidden group">
                        
                        {{-- Aksen Desain Pojok --}}
                        <div class="absolute top-0 right-0 w-24 h-24 bg-[#F8FAF7] rounded-bl-full -mr-4 -mt-4 transition-colors group-hover:bg-emerald-50"></div>

                        <div class="space-y-2 relative z-10">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl p-2 bg-[#F8FAF7] rounded-xl">⛰️</span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800 tracking-tight">{{ $lahan->nama_lahan }}</h3>
                                    <span class="text-[10px] font-bold text-slate-400 tracking-wide uppercase">{{ $lahan->lokasi_blok }}</span>
                                </div>
                            </div>
                            
                            <hr class="border-slate-100 my-2">

                            {{-- Relasi Komoditas Tanaman --}}
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-xs text-slate-400">Komoditas Aktif:</span>
                                @if($lahan->tanaman)
                                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                                        🌱 {{ $lahan->tanaman->nama_tanaman }}
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                                        🫙 Kosong / Istirahat
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Tombol Aksi (Edit & Hapus) --}}
                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-50 relative z-10">
                            <button @click="
                                editData = { 
                                    id: '{{ $lahan->id }}', 
                                    nama_lahan: '{{ $lahan->nama_lahan }}', 
                                    lokasi_blok: '{{ $lahan->lokasi_blok }}', 
                                    tanaman_id: '{{ $lahan->tanaman_id ?? '' }}' 
                                };
                                isEditModalOpen = true;
                            " class="p-2 text-xs font-medium text-amber-600 hover:bg-amber-50 rounded-xl transition-colors flex items-center gap-1">
                                ✏️ Edit
                            </button>

                            <form action="{{ route('lahan.destroy', $lahan->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus lahan ini? Semua riwayat aktivitas terkait lahan ini akan ikut terhapus');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-xs font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition-colors flex items-center gap-1">
                                    ❌ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 rounded-[28px] border border-dashed border-slate-200 text-center space-y-3">
                        <span class="text-4xl block">🏜️</span>
                        <h3 class="text-sm font-bold text-slate-700">Belum Ada Lahan Terdaftar</h3>
                        <p class="text-xs text-slate-400 max-w-xs mx-auto">Kamu belum mendaftarkan wilayah lahan kembangkanmu. Klik tombol Tambah Lahan di atas untuk memulai!</p>
                    </div>
                @endforelse
            </div>
        </main>

        {{-- ASIDE KANAN AREA (DETEKTOR STATUS TANAMAN AKTIF) --}}
        <aside class="w-full lg:w-80 xl:w-96 bg-white border-t lg:border-t-0 lg:border-l border-slate-100 p-6 md:p-8 space-y-8 flex flex-col justify-between shrink-0">
            <div class="space-y-6">
                {{-- Profil Singkat Petani Aktif --}}
                <div class="flex justify-between items-center bg-[#F8FAF7] p-4 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-[#183B2B] flex items-center justify-center text-white text-xs font-bold shrink-0">
                            {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 tracking-wide uppercase block">Petani Aktif</span>
                            <h2 class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Mitra Petani' }}</h2>
                        </div>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                {{-- 🧭 DETEKTOR REAL-TIME KOMODITAS YANG SEDANG DITANAM --}}
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xs font-bold text-slate-400 tracking-wide uppercase">Tanaman Terdeteksi di Lahan</h4>
                        <span class="text-[9px] bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded-sm animate-pulse">LIVE</span>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-2">
                        {{-- 💡 Mengambil list tanaman unik yang saat ini sedang aktif di lahan-lahan milik petani --}}
                        @forelse($lahans->pluck('tanaman')->filter()->unique('id') as $tanamanAktif)
                            <div class="flex items-center justify-between p-3 bg-emerald-50/50 rounded-xl border border-emerald-100/70">
                                <div class="flex items-center gap-3">
                                    <span class="text-base">🌱</span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">{{ $tanamanAktif->nama_tanaman }}</p>
                                        <p class="text-[9px] text-slate-400">Kategori: {{ $tanamanAktif->kategori }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold bg-[#183B2B] text-white px-2 py-0.5 rounded-md">
                                    {{ $lahans->where('tanaman_id', $tanamanAktif->id)->count() }} Lahan
                                </span>
                            </div>
                        @empty
                            <div class="p-6 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <span class="text-xl block mb-1">🏜️</span>
                                <p class="text-[11px] text-slate-400 italic">Semua lahanmu sedang beristirahat (Tidak ada tanaman aktif).</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <p class="text-[10px] text-slate-300 text-center pt-6 border-t border-slate-50">© 2026 AgFarm Ecosystem • AgroCare Design</p>
        </aside>

    </div>

    {{-- ==================== MODAL WINDOW: TAMBAH LAHAN ==================== --}}
    <div x-show="isCreateModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak>
        <div @click.away="isCreateModalOpen = false" class="bg-white rounded-[28px] max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">⛰️ Tambah Wilayah Lahan</h3>
                <button @click="isCreateModalOpen = false" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
            </div>
            
            <form action="{{ route('lahan.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Wilayah/Lahan</label>
                    <input type="text" name="nama_lahan" required placeholder="Contoh: Kebun Hidroponik Utara" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi / Nama Blok</label>
                    <input type="text" name="lokasi_blok" required placeholder="Contoh: Blok A-1" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih Komoditas Awal (Optional)</label>
                    {{-- 💡 Diambil dari koleksi unik tanaman yang tersedia di sistem --}}
                    <select name="tanaman_id" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs">
                        <option value="">-- Biarkan Kosong / Istirahat --</option>
                        @foreach($lahans->pluck('tanaman')->filter()->unique('id') as $t)
                            <option value="{{ $t->id }}">{{ $t->nama_tanaman }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="isCreateModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-50 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="bg-[#183B2B] hover:bg-[#23533d] text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all shadow-xs">Simpan Lahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL WINDOW: EDIT LAHAN ==================== --}}
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak>
        <div @click.away="isEditModalOpen = false" class="bg-white rounded-[28px] max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">✏️ Perbarui Data Lahan</h3>
                <button @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
            </div>
            
            <form :action="'/lahan/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Wilayah/Lahan</label>
                    <input type="text" name="nama_lahan" x-model="editData.nama_lahan" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi / Nama Blok</label>
                    <input type="text" name="lokasi_blok" x-model="editData.lokasi_blok" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ubah Komoditas</label>
                    <select name="tanaman_id" x-model="editData.tanaman_id" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] transition-colors shadow-2xs">
                        <option value="">-- Biarkan Kosong / Istirahat --</option>
                        @foreach($lahans->pluck('tanaman')->filter()->unique('id') as $t)
                            <option value="{{ $t->id }}">{{ $t->nama_tanaman }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-50 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all shadow-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection