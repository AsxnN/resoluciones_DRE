<?php
// filepath: app/Http/Middleware/CheckTipoAcceso.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTipoAcceso
{
    /**
     * Verificar que el usuario tenga el tipo de acceso correcto
     */
    public function handle(Request $request, Closure $next, string $tipoAcceso): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Verificar que el usuario esté activo
        if (!$user->i_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', '❌ Su cuenta está inactiva. Contacte al administrador.');
        }

        // Verificar tipo de acceso
        if ($user->tipo_acceso !== $tipoAcceso) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', '❌ No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}