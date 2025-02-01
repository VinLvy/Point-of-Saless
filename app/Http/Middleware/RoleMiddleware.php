<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Jika pengguna belum login, arahkan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Silakan login terlebih dahulu.');
        }

        // Ambil role pengguna
        $userRole = Auth::user()->role;

        // Jika role tidak sesuai, arahkan ke dashboard masing-masing
        if ($userRole !== $role) {
            if ($userRole == 'administrator') {
                return redirect()->route('admin.dashboard');
            } elseif ($userRole == 'kasir') {
                return redirect()->route('kasir.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors('Akses ditolak.');
            }
        }

        return $next($request);
    }
}
