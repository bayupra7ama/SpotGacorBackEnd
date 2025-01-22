<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna login dan memiliki role ADMIN
        if (Auth::check() && Auth::user()->roles === 'ADMIN') {
            return $next($request);
        }

        // Arahkan pengguna non-admin keluar
        return redirect('/')->with('error', 'Access denied. Only admins can access this page.');
    }
}
