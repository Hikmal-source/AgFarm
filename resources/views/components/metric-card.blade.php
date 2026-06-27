@props([
    'icon', 
    'title', 
    'value', 
    'isDark' => false, // Kita tambah prop baru buat deteksi kartu gelap
    'color' => 'bg-emerald-50 text-[#183B2B]'
])

<div 
    class="p-5 rounded-[24px] border flex flex-col justify-between hover:scale-[1.02] transition-all duration-300 min-w-[160px] flex-1
    {{ $isDark 
        ? 'bg-[#183B2B] text-white border-transparent shadow-lg shadow-emerald-950/20' 
        : 'bg-white text-slate-800 border-slate-100/80 shadow-xs hover:shadow-md' }}"
>
    <div class="flex justify-between items-start">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $isDark ? 'bg-white/10 text-white' : $color }}">
            {{ $icon }}
        </div>
        <button class="{{ $isDark ? 'text-white/40 hover:text-white/70' : 'text-slate-300 hover:text-slate-500' }} transition text-xs font-bold">•••</button>
    </div>
    
    <div class="mt-4 space-y-0.5">
        <p class="{{ $isDark ? 'text-white/60' : 'text-slate-400' }} text-[11px] font-medium tracking-wide uppercase">
            {{ $title }}
        </p>
        <p class="text-xl font-bold tracking-tight {{ $isDark ? 'text-white' : 'text-slate-800' }}">
            {{ $value }}
        </p>
    </div>
</div>