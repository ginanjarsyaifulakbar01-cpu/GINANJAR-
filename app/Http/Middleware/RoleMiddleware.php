<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Jika tidak login atau role tidak sesuai, tendang ke login atau dashboard depan
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            return redirect('/login')->with('error', 'Anda tidak punya akses ke halaman tersebut.');
        }

        return $next($request);
    }
}