@if(!Request::is('dashboard', 'dashboard/*', 'login', 'register', 'admin', 'admin/*', 'lahan', 'tanaman', 'panen', 'penjualan', 'forgot-password', 'reset-password', 'reset-password/*'))

<footer class="bg-white border-t border-slate-100 py-12">
    <div class="max-w-7xl mx-auto px-6 text-center text-slate-400 text-sm font-medium">
        &copy; {{ date('Y') }} <span class="text-[#166E4E] font-bold">AgFarm</span> Smart Agriculture. Made with ❤️ for Farmers.
    </div>
</footer>

@endif