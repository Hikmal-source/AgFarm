<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login atau rolenya bukan Admin, langsung tendang keluar balikkan eror 403
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Akses Ditolak! Halaman ini hanya untuk otoritas Admin.');
        }

        return $next($request);
    }
}