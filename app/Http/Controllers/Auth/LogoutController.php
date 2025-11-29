<?php
// filepath: app/Http/Controllers/Auth/LogoutController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Logout universal que redirige según tipo de usuario
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $tipoAcceso = $user?->tipo_acceso ?? 'cliente';
        $userId = $user?->id;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Auditoría
        if ($userId) {
            \App\Models\Auditoria::create([
                'tabla_afectada' => 'users',
                'id_registro' => $userId,
                'accion' => 'logout',
                'id_usuario' => $userId,
                'ip_address' => $request->ip(),
                'descripcion' => 'Logout',
            ]);
        }

        // Redirigir según tipo
        return redirect()->route(match($tipoAcceso) {
            'admin' => 'admin.login',
            'colaborador' => 'colaborador.login',
            'cliente' => 'cliente.login',
            default => 'login',
        });
    }
}