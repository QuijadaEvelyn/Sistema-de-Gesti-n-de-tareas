<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleStudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'alumno') {
            if (!Auth::user()->is_active) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Su cuenta de alumno se encuentra desactivada.');
            }
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Acceso denegado. Se requieren permisos de alumno.');
    }
}