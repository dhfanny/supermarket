<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek jika pengguna login dan memiliki role yang sesuai dengan yang dibutuhkan
        if (Auth::check()) {
            if (Auth::user()->role === $role || Auth::user()->role === 'admin') {
                return $next($request); // Jika role cocok atau admin, lanjutkan permintaan
            }
        }

        return redirect('/'); 
    }
}
