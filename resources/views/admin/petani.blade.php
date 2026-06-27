@extends('layouts.app')

@section('title', 'AgFarm - Manajemen Kemitraan Petani')
@section('content')

{{-- Inisialisasi State Alpine.js (Edit + Detail Pop-up Lokal) --}}
<div x-data="{ 
    isEditModalOpen: false,
    isDetailModalOpen: false,
    editData: { id: '', name: '', email: '' },
    detailData: { name: '', email: '', tgl: '' }
}" class="flex flex-col lg:flex-row min-h-screen max-w-full bg-[#F8FAF7] font-sans antialiased overflow-x-hidden">
    
    <x-admin.sidebar-dashboard />

    <div class="flex-1 flex flex-col lg:flex-row min-w-0 w-full">
        
        <main class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 min-w-0 overflow-y-auto">
            
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-2 shadow-2xs">
                    <span>🌿</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <header class="flex justify-between items-center gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight truncate">Manajemen Kemitraan</h1>
                    <p class="text-slate-400 text-xs truncate">Pantau, analisis kredensial, dan tata kelola akun hak akses para Petani aktif sistem⛰️</p>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 w-full">
                @forelse($semuaPetani as $p)
                    <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-2xs flex flex-col justify-between space-y-4 hover:shadow-xs transition-shadow relative overflow-hidden group">
                        
                        <div class="absolute top-0 right-0 w-24 h-24 bg-[#F8FAF7] rounded-bl-full -mr-4 -mt-4 transition-colors group-hover:bg-emerald-50"></div>

                        <div class="space-y-3 relative z-10">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-[#183B2B] flex items-center justify-center text-white text-base font-bold shadow-xs">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-bold text-slate-800 tracking-tight truncate">{{ $p->name }}</h3>
                                    <span class="text-[10px] font-bold text-slate-400 tracking-wide block truncate">{{ $p->email }}</span>
                                </div>
                            </div>
                            
                            <hr class="border-slate-100 my-2">

                            <div class="flex items-center justify-between pt-1">
                                <span class="text-xs text-slate-400">Terdaftar Tanggal:</span>
                                <span class="text-[11px] font-semibold text-slate-600 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-100">
                                    📅 {{ $p->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-50 relative z-10 gap-1">
                            
                            {{-- TOMBOL DETAIL: Langsung masukin data lewat Alpine.js tanpa loading --}}
                            <button @click="
                                detailData = { 
                                    name: '{{ $p->name }}', 
                                    email: '{{ $p->email }}',
                                    tgl: '{{ $p->created_at->format('d M Y') }}'
                                };
                                isDetailModalOpen = true;
                            " class="p-2 text-xs font-medium text-emerald-700 hover:bg-emerald-50 rounded-xl transition-colors flex items-center gap-1">
                                👁️ Detail
                            </button>

                            <div class="flex items-center gap-1">
                                <button @click="
                                    editData = { id: '{{ $p->id }}', name: '{{ $p->name }}', email: '{{ $p->email }}' };
                                    isEditModalOpen = true;
                                " class="p-2 text-xs font-medium text-amber-600 hover:bg-amber-50 rounded-xl transition-colors">
                                    ✏️ Edit
                                </button>

                                <form action="{{ route('admin.petani.destroy', $p->id) }}" method="POST" onsubmit="return confirm('🚨 Apakah Anda yakin ingin menghapus mitra ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-xs font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                        ❌ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 rounded-[28px] border border-dashed border-slate-200 text-center space-y-3">
                        <span class="text-4xl block">👥</span>
                        <h3 class="text-sm font-bold text-slate-700">Belum Ada Mitra Petani Terdaftar</h3>
                    </div>
                @endforelse
            </div>

            <div class="pt-4">
                {{ $semuaPetani->links() }}
            </div>
        </main>

        <aside class="w-full lg:w-80 xl:w-96 bg-white border-t lg:border-t-0 lg:border-l border-slate-100 p-6 md:p-8 space-y-8 flex flex-col justify-between shrink-0">
            <div class="space-y-6">
                <div class="flex justify-between items-center bg-[#F8FAF7] p-4 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-[#183B2B] flex items-center justify-center text-white text-xs font-bold shrink-0">A</div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 tracking-wide uppercase block">Otoritas Sistem</span>
                            <h2 class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Administrator' }}</h2>
                        </div>
                    </div>
                    <span class="text-[10px] bg-red-50 text-red-700 px-2 py-0.5 rounded font-bold border border-red-100">ADMIN</span>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 tracking-wide uppercase">Metrik Pendaftaran</h4>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/70 space-y-1">
                            <p class="text-xs text-slate-500 font-medium">Total Seluruh Mitra Petani</p>
                            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $totalPetani }} <span class="text-xs font-normal text-slate-400">Orang</span></h3>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                            <p class="text-xs text-slate-500 font-medium">Pendaftaran Bulan Ini</p>
                            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $petaniBulanIni }} <span class="text-xs font-normal text-slate-400">Baru</span></h3>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-[10px] text-slate-300 text-center pt-6 border-t border-slate-50">© 2026 AgFarm Ecosystem</p>
        </aside>

    </div>

    {{-- ==================== MODAL WINDOW: POP-UP DETAIL PETANI INSTAN ==================== --}}
    <div x-show="isDetailModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-cloak>
        <div @click.away="isDetailModalOpen = false" class="bg-white rounded-[28px] max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                <h3 class="text-base font-bold text-slate-800">👁️ Detail Kredensial Mitra</h3>
                <button @click="isDetailModalOpen = false" class="text-slate-400 hover:text-slate-600 text-sm bg-slate-50 w-6 h-6 rounded-full flex items-center justify-center">✕</button>
            </div>
            
            <div class="space-y-3 pt-2">
                <div class="bg-slate-50 p-4 rounded-xl space-y-2">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Lengkap</span>
                        <p class="text-sm font-bold text-slate-700" x-text="detailData.name"></p>
                    </div>
                    <hr class="border-slate-200/50">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Email</span>
                        <p class="text-xs font-medium text-slate-600" x-text="detailData.email"></p>
                    </div>
                    <hr class="border-slate-200/50">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tanggal Bergabung</span>
                        <p class="text-xs font-medium text-slate-600" x-text="'📅 ' + detailData.tgl"></p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="button" @click="isDetailModalOpen = false" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-xs font-semibold">Tutup</button>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL WINDOW: EDIT PROFIL/PASSWORD PETANI ==================== --}}
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs" x-cloak>
        <div @click.away="isEditModalOpen = false" class="bg-white rounded-[28px] max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex justify-between items-center">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">✏️ Perbarui Kredensial Mitra</h3>
                <button @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
            </div>
            <form :action="'/admin/petani/' + editData.id" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap Petani</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat Email Aktif</label>
                    <input type="email" name="email" x-model="editData.email" required class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Reset Password Baru (Optional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs focus:outline-hidden">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-50 rounded-xl">Batal</button>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-xs">Simpan Kredensial</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection