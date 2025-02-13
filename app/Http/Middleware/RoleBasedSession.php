<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class RoleBasedSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Gunakan facade Auth untuk memeriksa autentikasi
        if (Auth::check()) {
            $user = Auth::user();

            // Atur nama cookie session berdasarkan role
            if ($user->role === 0) { // Admin
                Config::set('session.cookie', 'admin_session');
            } else { // User biasa
                Config::set('session.cookie', 'user_session');
            }
        }

        return $next($request);
    }
}
