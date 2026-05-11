<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Si no está logueado, fuera.
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Si su rol NO es el que pedimos (ej: 'barbero'), error 403.
        if (Auth::user()->role !== $role) {
            abort(403, 'No tienes permiso para entrar aquí.');
        }

        return $next($request);
    }
}
