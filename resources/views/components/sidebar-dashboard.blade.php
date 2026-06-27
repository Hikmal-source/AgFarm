{{-- PERBAIKAN: Mengubah struktur utama agar menjadi bottom nav di mobile (hidden / fixed bawah) dan sidebar di desktop (lg:) --}}
<aside x-data="{ isExpanded: false }" @click.away="isExpanded = false" 
    :class="isExpanded ? 'lg:w-64' : 'lg:w-20'"
    class="fixed bottom-0 left-0 right-0 h-16 w-full bg-white border-t border-slate-100 flex flex-row justify-between items-center px-2 py-0 z-50 shadow-lg lg:relative lg:bottom-auto lg:left-auto lg:right-auto lg:h-auto lg:min-h-screen lg:w-auto lg:flex-col lg:py-8 lg:border-t-0 lg:border-r lg:shadow-2xs transition-all duration-300 ease-in-out">
    
    {{-- BUTTON TOGGLE EXPAND: Disembunyikan di mobile (hidden), hanya muncul di desktop (lg:flex) --}}
    <button @click="isExpanded = !isExpanded"
        class="hidden lg:flex absolute -right-3 top-9 w-6 h-6 bg-white border border-slate-100 rounded-full items-center justify-center text-[10px] shadow-xs text-slate-400 hover:text-[#183B2B] transition-transform duration-300"
        :class="isExpanded ? 'rotate-180' : ''">
        &gt;
    </button>

    {{-- WRAPPER NAVIGASI UTAMA --}}
    <div class="flex flex-row lg:flex-col gap-0 lg:gap-8 w-full h-full lg:h-auto items-center lg:items-start">
        {{-- Tiga Titik Dekorasi Mac: Disembunyikan di mobile, hanya di desktop --}}
        <div class="hidden lg:flex gap-1.5 px-6 transition-all duration-300"
            :class="isExpanded ? 'justify-start' : 'justify-center'">
            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
        </div>

        {{-- Menu Links: Berjejer horizontal di mobile, vertikal di desktop --}}
        <nav class="flex flex-row lg:flex-col gap-1 sm:gap-2 lg:gap-3 w-full px-2 lg:px-3 pt-0 lg:pt-4 justify-around lg:justify-start items-center">

            {{-- 🏠 MENU DASHBOARD --}}
            <a href="/dashboard"
                class="flex items-center rounded-xl lg:rounded-2xl h-11 lg:h-12 transition-all duration-300 group relative shrink-0
                {{ request()->is('dashboard') || request()->is('/') ? 'bg-[#183B2B] text-white shadow-md lg:shadow-lg lg:shadow-emerald-950/10' : 'text-slate-400 hover:bg-slate-50 hover:text-[#183B2B]' }}"
                :class="isExpanded ? 'lg:px-4 lg:w-full lg:gap-4' : 'w-11 lg:w-12 justify-center lg:mx-auto'">
                <span class="text-lg lg:text-xl shrink-0">🏠</span>
                <span x-show="isExpanded" class="hidden lg:inline text-xs font-semibold tracking-wide whitespace-nowrap">
                    Dashboard
                </span>
                {{-- Tooltip Desktop --}}
                <span x-show="!isExpanded" class="hidden lg:block absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                    Dashboard
                </span>
            </a>

            {{-- 🌱 DATA TANAMAN --}}
            <a href="/tanaman"
                class="flex items-center rounded-xl lg:rounded-2xl h-11 lg:h-12 transition-all duration-300 group relative shrink-0
                {{ request()->is('tanaman*') ? 'bg-[#183B2B] text-white shadow-md lg:shadow-lg' : 'text-slate-400 hover:bg-slate-50 hover:text-[#183B2B]' }}"
                :class="isExpanded ? 'lg:px-4 lg:w-full lg:gap-4' : 'w-11 lg:w-12 justify-center lg:mx-auto'">
                <span class="text-lg lg:text-xl shrink-0">🌱</span>
                <span x-show="isExpanded" class="hidden lg:inline text-xs font-semibold tracking-wide whitespace-nowrap">
                    Data Tanaman
                </span>
                <span x-show="!isExpanded" class="hidden lg:block absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                    Data Tanaman
                </span>
            </a>

            {{-- 🌾 HASIL PANEN --}}
            <a href="/panen"
                class="flex items-center rounded-xl lg:rounded-2xl h-11 lg:h-12 transition-all duration-300 group relative shrink-0 text-slate-400 hover:bg-slate-50 hover:text-[#183B2B]"
                :class="isExpanded ? 'lg:px-4 lg:w-full lg:gap-4' : 'w-11 lg:w-12 justify-center lg:mx-auto'">
                <span class="text-lg lg:text-xl shrink-0">🌾</span>
                <span x-show="isExpanded" class="hidden lg:inline text-xs font-semibold tracking-wide whitespace-nowrap">
                    Hasil Panen
                </span>
                <span x-show="!isExpanded" class="hidden lg:block absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                    Hasil Panen
                </span>
            </a>

            {{-- 🧾 DATA PENJUALAN --}}
            <a href="/penjualan"
                class="flex items-center rounded-xl lg:rounded-2xl h-11 lg:h-12 transition-all duration-300 group relative shrink-0 text-slate-400 hover:bg-slate-50 hover:text-[#183B2B]"
                :class="isExpanded ? 'lg:px-4 lg:w-full lg:gap-4' : 'w-11 lg:w-12 justify-center lg:mx-auto'">
                <span class="text-lg lg:text-xl shrink-0">🧾</span>
                <span x-show="isExpanded" class="hidden lg:inline text-xs font-semibold tracking-wide whitespace-nowrap">
                    Penjualan
                </span>
                <span x-show="!isExpanded" class="hidden lg:block absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                    Data Penjualan
                </span>
            </a>

            {{-- ⛰️ KELOLA LAHAN --}}
            <a href="/lahan"
                class="flex items-center rounded-xl lg:rounded-2xl h-11 lg:h-12 transition-all duration-300 group relative shrink-0
                {{ request()->is('lahan*') ? 'bg-[#183B2B] text-white shadow-md lg:shadow-lg lg:shadow-emerald-950/10' : 'text-slate-400 hover:bg-slate-50 hover:text-[#183B2B]' }}"
                :class="isExpanded ? 'lg:px-4 lg:w-full lg:gap-4' : 'w-11 lg:w-12 justify-center lg:mx-auto'">
                <span class="text-lg lg:text-xl shrink-0">⛰️</span>
                <span x-show="isExpanded" class="hidden lg:inline text-xs font-semibold tracking-wide whitespace-nowrap">
                    Kelola Lahan
                </span>
                <span x-show="!isExpanded" class="hidden lg:block absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                    Kelola Lahan
                </span>
            </a>

            {{-- ⚙️ SETTINGS (Diselipkan ke row menu khusus mobile agar sejajar) --}}
            <a href="/"
                class="flex lg:hidden items-center rounded-xl h-11 transition-all duration-300 text-slate-400 hover:bg-red-50 hover:text-red-600 w-11 justify-center shrink-0 shadow-3xs bg-slate-50/50">
                <span class="text-lg shrink-0">🚪</span>
            </a>

        </nav>
    </div>

    {{-- ⚙️ SETTINGS DESKTOP: Hanya muncul di komputer (lg:block), di mobile hilang --}}
    <div class="hidden lg:block w-full px-3">
        <a href="/"
            class="flex items-center text-slate-400 hover:bg-red-50 hover:text-red-600 rounded-2xl h-12 transition-all duration-300 group relative"
            :class="isExpanded ? 'px-4 w-full gap-4' : 'w-12 mx-auto justify-center'">
            <span class="text-xl shrink-0">🚪</span>
            <span x-show="isExpanded" class="text-xs font-semibold tracking-wide whitespace-nowrap">
                Logout
            </span>
            <span x-show="!isExpanded" class="absolute left-16 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                Logout
            </span>
        </a>
    </div>
</aside>