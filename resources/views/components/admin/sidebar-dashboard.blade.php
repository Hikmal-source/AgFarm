<aside 
    x-data="{ isExpanded: false }"
    @click.away="isExpanded = false"
    :class="isExpanded ? 'w-64' : 'w-20'"
    class="min-h-screen bg-white border-r border-slate-100 flex flex-col justify-between py-8 shrink-0 relative transition-all duration-300 ease-in-out z-50 shadow-2xs"
>
    <button 
        @click="isExpanded = !isExpanded" 
        class="absolute -right-3 top-9 w-6 h-6 bg-white border border-slate-100 rounded-full flex items-center justify-center text-[10px] shadow-xs text-slate-400 hover:text-[#183B2B] transition-transform duration-300"
        :class="isExpanded ? 'rotate-180' : ''"
    >
        >
    </button>

    <div class="flex flex-col gap-8 w-full">
        <div class="flex gap-1.5 px-6 transition-all duration-300" :class="isExpanded ? 'justify-start' : 'justify-center'">
            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
        </div>
        
        <nav class="flex flex-col gap-3 w-full px-3 pt-4">
            
            {{-- 🏠 MENU DASHBOARD (Aktif jika di root / atau /admin/dashboard) --}}
            <a href="/admin/dashboard" 
               class="flex items-center rounded-2xl h-12 transition-all duration-300 group relative
               {{ request()->is('admin/dashboard') || request()->is('/') ? 'bg-[#183B2B] text-white shadow-lg shadow-emerald-950/10' : 'text-slate-400 hover:bg-slate-50 hover:text-[#183B2B]' }}"
               :class="isExpanded ? 'px-4 w-full gap-4' : 'w-12 mx-auto justify-center'"
            >
                <span class="text-xl shrink-0">🏠</span>
                <span x-show="isExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0" class="text-xs font-semibold tracking-wide whitespace-nowrap">
                    Dashboard
                </span>
                <span x-show="!isExpanded" class="absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                    Dashboard
                </span>
            </a>
            
            {{-- 📊 MENU ANALYTICS / AUDIT LOGS (Otomatis Aktif & Menyala Hijau) --}}
            <a href="/admin/audit-logs" 
               class="flex items-center rounded-2xl h-12 transition-all duration-300 group relative
               {{ request()->is('admin/audit-logs*') ? 'bg-[#183B2B] text-white shadow-lg shadow-emerald-950/10' : 'text-slate-400 hover:bg-slate-50 hover:text-[#183B2B]' }}"
               :class="isExpanded ? 'px-4 w-full gap-4' : 'w-12 mx-auto justify-center'"
            >
                <span class="text-xl shrink-0">📊</span>
                <span x-show="isExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0" class="text-xs font-semibold tracking-wide whitespace-nowrap">
                    Analytics
                </span>
                <span x-show="!isExpanded" class="absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                    Analytics
                </span>
            </a>

            <a href="/admin/petani" 
               class="flex items-center rounded-2xl h-12 transition-all duration-300 group relative
               {{ request()->is('admin/petani*') ? 'bg-[#183B2B] text-white shadow-lg shadow-emerald-950/10' : 'text-slate-400 hover:bg-slate-50 hover:text-[#183B2B]' }}"
               :class="isExpanded ? 'px-4 w-full gap-4' : 'w-12 mx-auto justify-center'"
            >
                <span class="text-xl shrink-0">👨‍👧‍👦</span>
                <span x-show="isExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0" class="text-xs font-semibold tracking-wide whitespace-nowrap">
                    Mitra
                </span>
                <span x-show="!isExpanded" class="absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                    Mitra
                </span>
            </a>
        </nav>
    </div>

    <div class="w-full px-3">
        <a href="#" 
           class="flex items-center text-slate-400 hover:bg-red-50 hover:text-red-600 rounded-2xl h-12 transition-all duration-300 group relative"
           :class="isExpanded ? 'px-4 w-full gap-4' : 'w-12 mx-auto justify-center'"
        >
            <span class="text-xl shrink-0">⚙️</span>
            <span x-show="isExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0" class="text-xs font-semibold tracking-wide whitespace-nowrap">
                Settings
            </span>
            <span x-show="!isExpanded" class="absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                Settings
            </span>
        </a>
    </div>
</aside>