@extends('layouts.app')

@section('title', 'AgFarm - Hubungi Kami & Konsultasi Pertanian Pintar')

@section('content')
{{-- ==================== HERO SECTION KONTAK ==================== --}}
<section class="relative h-[40vh] md:h-[50vh] flex items-center bg-[#183B2B] text-white px-6 md:px-16 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://i.pinimg.com/1200x/cf/1d/b8/cf1db89fc8b650f69258258cb3423655.jpg" 
             class="w-full h-full object-cover object-center transform scale-105" alt="AgFarm Hubungi Kami">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/30 to-[#F4F6F3]"></div>
    </div>

    <div class="max-w-7xl mx-auto w-full relative z-10 pt-16">
        <div class="inline-flex items-center gap-1.5 border border-white/20 bg-white/10 text-white backdrop-blur-xs px-3 py-1 rounded-full text-[11px] font-medium mb-4 shadow-2xs">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Layanan Dukungan
        </div>
        <h1 class="text-4xl md:text-6xl font-semibold tracking-tight leading-none drop-shadow-sm">
            Mari Bertemu & <br>Tumbuh Bersama
        </h1>
    </div>
</section>

{{-- ==================== MAIN CONTENT SECTION ==================== --}}
<section class="bg-[#F4F6F3] pb-24 px-6 md:px-16 relative z-20 -mt-12">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-12 gap-8 items-start">
        
        {{-- KANAN / FORM HUBUNGI KAMI --}}
        <div class="lg:col-span-7 bg-white rounded-[32px] p-6 md:p-10 border border-slate-200/60 shadow-xs space-y-6">
            <div class="space-y-2">
                <h3 class="text-2xl font-bold text-[#183B2B] tracking-tight">Kirimkan Pesan Anda</h3>
                <p class="text-slate-400 text-xs">Punya pertanyaan seputar teknologi IoT, kemitraan lahan, atau kendala sistem? Tim agronomis kami siap membalas dalam waktu kurang dari 24 jam.</p>
            </div>

            @if(session('success'))
                <div class="p-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-2 shadow-2xs">
                    <span>🌿</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Masukkan nama Anda" 
                               class="w-full bg-[#F4F6F3] border border-slate-200/60 p-3.5 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] text-slate-700 transition-colors">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat Email</label>
                        <input type="email" name="email" required placeholder="nama@email.com" 
                               class="w-full bg-[#F4F6F3] border border-slate-200/60 p-3.5 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] text-slate-700 transition-colors">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Subjek Kepentingan</label>
                    <select name="subject" class="w-full bg-[#F4F6F3] border border-slate-200/60 p-3.5 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] text-slate-600 transition-colors">
                        <option value="konsultasi">Konsultasi Lahan & IoT</option>
                        <option value="kemitraan">Kemitraan Kelompok Tani</option>
                        <option value="teknis">Kendala Akun & Aplikasi</option>
                        <option value="lainnya">Pertanyaan Umum</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Isi Pesan Pendukung</label>
                    <textarea name="message" rows="5" required placeholder="Ceritakan detail kebutuhan perkebunan atau pertanyaan Anda di sini..." 
                              class="w-full bg-[#F4F6F3] border border-slate-200/60 p-3.5 rounded-xl text-xs focus:outline-hidden focus:border-[#183B2B] text-slate-700 transition-colors resize-none"></textarea>
                </div>

                <button type="submit" class="w-full bg-[#183B2B] text-white font-medium p-4 rounded-xl text-xs hover:bg-black transition-colors shadow-xs flex items-center justify-center gap-2">
                    Kirim Pesan Layanan <span class="text-sm">↗</span>
                </button>
            </form>
        </div>

        {{-- KIRI / INFORMASI KONTAK & ASIDE --}}
        <div class="lg:col-span-5 space-y-6">
            
            {{-- KARTU INFO KONTAK --}}
            <div class="bg-white rounded-[32px] p-6 md:p-8 border border-slate-200/60 shadow-2xs space-y-6">
                <div class="inline-flex items-center gap-1.5 border border-slate-200 bg-[#F4F6F3] text-slate-600 px-3 py-1 rounded-full text-[11px] font-medium shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span> Kontak Resmi
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-[#E8F5E9] text-[#166E4E] rounded-xl flex items-center justify-center shrink-0 text-sm">📍</div>
                        <div class="space-y-0.5">
                            <h4 class="font-bold text-xs text-[#183B2B] uppercase tracking-wider">Kantor Operasional</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">Politeknik Negeri Cilacap</p>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-[#E8F5E9] text-[#166E4E] rounded-xl flex items-center justify-center shrink-0 text-sm">✉️</div>
                        <div class="space-y-0.5">
                            <h4 class="font-bold text-xs text-[#183B2B] uppercase tracking-wider">Korespondensi Email</h4>
                            <p class="text-slate-500 text-xs truncate">support@agfarm-ecosystem.id</p>
                            <p class="text-slate-400 text-[10px]">Kemitraan: hikmal.stu@pnc.ac.id</p>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-[#E8F5E9] text-[#166E4E] rounded-xl flex items-center justify-center shrink-0 text-sm">📞</div>
                        <div class="space-y-0.5">
                            <h4 class="font-bold text-xs text-[#183B2B] uppercase tracking-wider">Hotline Pertanian</h4>
                            <p class="text-slate-500 text-xs">+62 (24) 8765-4321</p>
                            <p class="text-slate-400 text-[10px]">Senin - Jumat | 08.00 - 16.00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MINI BANNER BANNER PENCAPAIAN --}}
            <div class="relative w-full h-[220px] rounded-[32px] overflow-hidden shadow-md group">
                <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=600&q=80" 
                     class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105" alt="Konsultasi Lapangan">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex flex-col justify-end p-6 text-white space-y-1">
                    <span class="bg-[#2E7D32] text-white text-[9px] px-2.5 py-0.5 rounded-full font-medium w-max mb-1">Butuh Respon Cepat?</span>
                    <h4 class="text-base font-bold">Konsultasi via WhatsApp Mandiri</h4>
                    <p class="text-white/80 text-[11px] leading-relaxed">Hubungi asisten teknologi tani lapangan untuk panduan darurat integrasi lahan harian.</p>
                    <a href="https://wa.me/6287654321" target="_blank" class="text-[11px] text-emerald-400 font-bold flex items-center gap-0.5 pt-1 hover:underline">
                        Mulai Chat Sekarang <span class="text-[9px]">↗</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection