<?php
// filepath: app/Http/Middleware/EnsureUserHasPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Middleware para verificar múltiples permisos (OR logic)
     */
    public function handle(Request $request, Closure $next, string ...$permisos): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin siempre pasa
        if ($user->tipo_acceso === 'admin') {
            return $next($request);
        }

        // Verificar si tiene al menos uno de los permisos
        foreach ($permisos as $permiso) {
            if ($user->can($permiso)) {
                return $next($request);
            }
        }

        abort(403, '⛔ No tiene los permisos necesarios.');
    }
}