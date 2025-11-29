<?php
// filepath: app/Http/Middleware/CheckPermiso.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermiso
{
    /**
     * Verificar permisos dinámicos usando Spatie
     */
    public function handle(Request $request, Closure $next, string $permiso): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin tiene todos los permisos
        if ($user->tipo_acceso === 'admin') {
            return $next($request);
        }

        // Verificar permiso específico
        if (!$user->can($permiso)) {
            abort(403, '⛔ No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}