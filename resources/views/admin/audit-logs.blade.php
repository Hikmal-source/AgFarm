@extends('layouts.app')

@section('title', 'AgFarm - Log Audit Keamanan Sistem')

@section('content')
<div class="flex flex-col lg:flex-row min-h-screen max-w-full bg-[#F8FAF7] font-sans antialiased overflow-x-hidden">
    
    <x-admin.sidebar-dashboard />

    <div class="flex-1 flex flex-col min-w-0 w-full">
        <main class="flex-1 p-4 sm:p-6 md:p-8 space-y-6 min-w-0 overflow-y-auto">
            
            <header class="flex justify-between items-center gap-4 border-b border-slate-100 pb-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight truncate">Audit Log Sistem</h1>
                    <p class="text-slate-400 text-xs truncate">Rekam jejak aktivitas dan keamanan infrastruktur AgFarm 🛡️</p>
                </div>
                <div>
                    <span class="text-xs bg-red-100 text-red-800 font-bold px-3 py-1 rounded-full animate-pulse">Live Secure</span>
                </div>
            </header>

            <div class="bg-white rounded-[28px] border border-slate-100 shadow-2xs p-6 space-y-4">
                <div class="overflow-x-auto min-w-full">
                    <table class="min-w-full text-xs text-left text-slate-600">
                        <thead class="text-[10px] uppercase text-slate-400 border-b border-slate-100 bg-slate-50/50">
                            <tr>
                                <th class="px-4 py-3 font-bold">Aktivitas</th>
                                <th class="px-4 py-3 font-bold">Deskripsi Log</th>
                                <th class="px-4 py-3 font-bold">Waktu</th>
                                <th class="px-4 py-3 font-bold text-right">Alamat IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($auditLogs as $log)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if($log->activity == 'Login Berhasil')
                                                <span class="text-sm p-1 bg-emerald-50 rounded-md">🔑</span>
                                                <span class="font-bold text-emerald-800">{{ $log->activity }}</span>
                                            @elseif($log->activity == 'Gagal Login')
                                                <span class="text-sm p-1 bg-red-50 rounded-md">⚠️</span>
                                                <span class="font-bold text-red-600">{{ $log->activity }}</span>
                                            @elseif($log->activity == 'Logout')
                                                <span class="text-sm p-1 bg-amber-50 rounded-md">🚪</span>
                                                <span class="font-bold text-amber-700">{{ $log->activity }}</span>
                                            @else
                                                <span class="text-sm p-1 bg-blue-50 rounded-md">🛡️</span>
                                                <span class="font-bold text-blue-800">{{ $log->activity }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 max-w-md break-words">
                                        {{ $log->description }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-400 whitespace-nowrap">
                                        {{ $log->created_at->format('d M Y, H:i:s') }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono text-slate-400 whitespace-nowrap">
                                        {{ $log->ip_address ?? '127.0.0.1' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-slate-400">Belum ada log masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Navigasi Halaman (Pagination) --}}
                <div class="pt-4 border-t border-slate-50">
                    {{ $auditLogs->links() }}
                </div>
            </div>

        </main>
    </div>
</div>
@endsection