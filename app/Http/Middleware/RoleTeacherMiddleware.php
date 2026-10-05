<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleTeacherMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'docente') {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Acceso denegado. Se requieren permisos de docente.');
    }
}