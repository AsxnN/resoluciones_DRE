<?php
// filepath: app/Http/Middleware/VerificarPerfilCompleto.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarPerfilCompleto
{
    /**
     * Verificar que el colaborador tenga su perfil completo
     * Si no, redirigir a completar perfil
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Verificar si tiene persona asociada
        if (!$user || !$user->persona) {
            return redirect()->route('colaborador.profile.edit')
                ->with('warning', '⚠️ No tienes datos de persona registrados. Por favor completa tu perfil.');
        }
        
        // Verificar si los datos están completos
        if (!$user->persona->datos_completos) {
            return redirect()->route('colaborador.profile.edit')
                ->with('warning', '⚠️ Debes completar tu perfil para acceder al sistema.');
        }
        
        return $next($request);
    }
}