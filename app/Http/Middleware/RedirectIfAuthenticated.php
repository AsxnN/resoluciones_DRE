<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Redirigir según tipo de usuario
                return redirect()->route(match($user->tipo_acceso) {
                    'admin' => 'admin.dashboard',
                    'colaborador' => 'colaborador.dashboard',
                    'cliente' => 'cliente.dashboard',
                    default => 'login',
                });
            }
        }

        return $next($request);
    }
}