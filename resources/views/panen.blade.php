@extends('layouts.app')

@section('title', 'AgFarm - Log Hasil Panen')
@section('content')

{{-- Inisialisasi State Alpine.js untuk Modal Tambah & Edit Catatan Panen --}}
<div x-data="{ 
    isCreateModalOpen: false, 
    isEditModalOpen: false,
    editData: { id: '', lahan_id: '', tanaman_id: '', jumlah_panen: '', tanggal_panen: '', kualitas: '' }
}" class="flex flex-col lg:flex-row min-h-screen max-w-full bg-[#FAFCFA] font-sans antialiased overflow-x-hidden">
    
    <x-sidebar-dashboard />

    {{-- Konten Utama --}}
    <div class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 min-w-0 overflow-y-auto pb-24 lg:pb-8">
        
        {{-- Flash Alert Notifikasi --}}
        @if(session('success'))
            <div class="p-4 text-sm text-[#183B2B] rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-2 shadow-2xs animate-fade-in">
                <span>🧺</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header Menu Atas --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-[24px] border border-slate-100 shadow-2xs">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    🌾  Hasil Produksi Panen
                </h1>
                <p class="text-slate-400 text-xs mt-0.5">Catat dan pantau histori tonase hasil tani langsung dari area komoditas lahan.</p>
            </div>
            <button @click="isCreateModalOpen = true" class="w-full sm:w-auto bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-3 rounded-xl text-xs font-bold shadow-sm flex items-center justify-center gap-2 transition-all transform active:scale-95">
                <span>✨</span> Rekam Hasil Panen
            </button>
        </header>

        {{-- Papan Metrik Ringkasan Atas --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">Total Produksi</span>
                <span class="text-xl font-extrabold text-slate-800 mt-1 block">
                    {{ $daftarPanen->sum('jumlah_panen') }} Kg
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">🌟 Kualitas Premium</span>
                <span class="text-xl font-extrabold text-emerald-700 mt-1 block">
                    {{ $daftarPanen->whereIn('kualitas', ['Grade A', 'A', 'Premium'])->sum('jumlah_panen') }} Kg
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs">
                <span class="text-xs font-bold text-slate-400 block uppercase">📅 Frekuensi Panen</span>
                <span class="text-xl font-extrabold text-amber-600 mt-1 block">
                    {{ $daftarPanen->count() }} Kali
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-3xs bg-linear-to-br from-emerald-950 to-emerald-900 text-white border-0">
                <span class="text-xs font-medium text-emerald-200 block uppercase">Siklus Agraria</span>
                <span class="text-xl font-bold mt-1 block flex items-center gap-1.5">
                    Sinkron <span class="w-2 h-2 rounded-full bg-emerald-400 block animate-ping"></span>
                </span>
            </div>
        </div>

        {{-- Daftar Baris Panen (Striped List) --}}
        <div class="bg-white rounded-[28px] border border-slate-100 shadow-2xs overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-700">Histori Penuaian Hasil Bumi</h3>
                <span class="text-[10px] bg-slate-100 text-slate-500 font-bold px-2 py-1 rounded-md">REAL-TIME</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($daftarPanen as $panen)
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/60 transition-colors">
                        
                        {{-- Kolom Info Kiri --}}
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0
                                {{ $panen->tanaman->kategori === 'Sayuran' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $panen->tanaman->kategori === 'Sayuran' ? '🥬' : '🍎' }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-800 tracking-tight">
                                    {{ $panen->tanaman->nama_tanaman }} — <span class="text-emerald-700 font-extrabold">{{ $panen->jumlah_panen }} Kg</span>
                                </h4>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-semibold text-slate-400">ID: #P-{{ $panen->id }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-sm font-bold tracking-wide uppercase bg-slate-100 text-slate-700">
                                        📍 {{ $panen->lahan->nama_lahan }}
                                    </span>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-sm font-medium bg-emerald-100 text-emerald-800 font-bold">
                                        ✨ {{ $panen->kualitas }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        📅 {{ \Carbon\Carbon::parse($panen->tanggal_panen)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Aksi Kanan --}}
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100">
                            <button @click="
                                editData = { 
                                    id: '{{ $panen->id }}', 
                                    lahan_id: '{{ $panen->lahan_id }}', 
                                    tanaman_id: '{{ $panen->tanaman_id }}',
                                    jumlah_panen: '{{ $panen->jumlah_panen }}',
                                    tanggal_panen: '{{ $panen->tanggal_panen }}',
                                    kualitas: '{{ $panen->kualitas }}'
                                };
                                isEditModalOpen = true;
                            " class="px-3 py-1.5 text-xs font-semibold text-amber-600 hover:bg-amber-50 rounded-lg transition-colors flex items-center gap-1">
                                🔧 Modifikasi
                            </button>

                            {{-- Form Delete Catatan Panen --}}
                            <form action="{{ route('panen.destroy', $panen->id) }}" method="POST" onsubmit="return confirm('Hapus rekam log panen ini? Tindakan ini akan tercatat pada sistem audit.');">
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
                        <span class="text-4xl block">🌾</span>
                        <h4 class="text-xs font-bold text-slate-600">Belum Ada Rekam Hasil Panen</h4>
                        <p class="text-[11px] text-slate-400">Tekan tombol di atas untuk mencatatkan hasil panen dari lahan milikmu.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <p class="text-[10px] text-slate-300 text-center pt-4">© 2026 AgFarm Ecosystem • AgroCare Design</p>
    </div>

    {{-- ==================== MODAL WINDOW: REKAM PANEN BARU ==================== --}}
    <div x-show="isCreateModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-transition x-cloak>
        <div @click.away="isCreateModalOpen = false" class="bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-800 flex items-center gap-1.5">🌾 Rekam Hasil Panen</h3>
                <button @click="isCreateModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xs">✕</button>
            </div>
            
            <form action="{{ route('panen.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Asal Lahan</label>
                    <select name="lahan_id" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                        <option value="">-- Pilih Asal Lahan Produksi --</option>
                        @foreach($daftarLahan as $lahan)
                            <option value="{{ $lahan->id }}">⛰️ {{ $lahan->nama_lahan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Komoditas Tanaman</label>
                    <select name="tanaman_id" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                        <option value="">-- Pilih Komoditas --</option>
                        @foreach($daftarTanaman as $tanaman)
                            <option value="{{ $tanaman->id }}">{{ $tanaman->kategori === 'Sayuran' ? '🥬' : '🍎' }} {{ $tanaman->nama_tanaman }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jumlah Hasil (Kg)</label>
                    <input type="number" name="jumlah_panen" min="1" required placeholder="Contoh: 150" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Panen</label>
                    <input type="date" name="tanggal_panen" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Grade / Kualitas</label>
                    <select name="kualitas" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-emerald-700 transition-colors">
                        <option value="Grade A">Grade A (Premium)</option>
                        <option value="Grade B">Grade B (Medium)</option>
                        <option value="Grade C">Grade C (Rendah)</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="isCreateModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-400 rounded-xl">Batal</button>
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-xs">Simpan Log</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL WINDOW: EDIT DATA PANEN ==================== --}}
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-transition x-cloak>
        <div @click.away="isEditModalOpen = false" class="bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-800 flex items-center gap-1.5">🔧 Modifikasi Log Panen</h3>
                <button @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xs">✕</button>
            </div>
            
            <form :action="'/panen/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Asal Lahan</label>
                    <select name="lahan_id" x-model="editData.lahan_id" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                        @foreach($daftarLahan as $lahan)
                            <option value="{{ $lahan->id }}">⛰️ {{ $lahan->nama_lahan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Komoditas Tanaman</label>
                    <select name="tanaman_id" x-model="editData.tanaman_id" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                        @foreach($daftarTanaman as $tanaman)
                            <option value="{{ $tanaman->id }}">{{ $tanaman->kategori === 'Sayuran' ? '🥬' : '🍎' }} {{ $tanaman->nama_tanaman }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jumlah Hasil (Kg)</label>
                    <input type="number" name="jumlah_panen" x-model="editData.jumlah_panen" min="1" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Panen</label>
                    <input type="date" name="tanggal_panen" x-model="editData.tanggal_panen" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Grade / Kualitas</label>
                    <select name="kualitas" x-model="editData.kualitas" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden focus:border-amber-500 transition-colors">
                        <option value="Grade A">Grade A (Premium)</option>
                        <option value="Grade B">Grade B (Medium)</option>
                        <option value="Grade C">Grade C (Rendah)</option>
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