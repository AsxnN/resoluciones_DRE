<?php
// filepath: app/Http/Controllers/Cliente/DashboardController.php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Queja;
use App\Models\Resolucion;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            return redirect()->route('login')
                ->with('error', '❌ No tiene perfil de cliente asociado');
        }

        $persona = $cliente->persona;

        // Estadísticas del cliente
        $stats = [
            'total_resoluciones' => Resolucion::whereHas('personasInvolucradas', function($q) use ($persona) {
                $q->where('persona.id_persona', $persona->id_persona);
            })->count(),

            'ultima_consulta' => $user->ultima_sesion, // ← Agregar esta línea

            'busquedas' => 0, // ← Agregar esta línea (puedes implementar lógica real después)

            'mis_quejas' => Queja::where('id_cliente', $cliente->id_cliente)->count(),

            'notificaciones_pendientes' => Notificacion::where('id_usuario', $user->id)
                ->where('i_leido', false)
                ->count(),
        ];

        // Resoluciones recientes
        $resolucionesRecientes = Resolucion::with(['estado', 'tipoResolucion'])
            ->whereHas('personasInvolucradas', function($q) use ($persona) {
                $q->where('persona.id_persona', $persona->id_persona);
            })
            ->orderBy('fecha_resolucion', 'desc')
            ->limit(5)
            ->get();

        // Quejas recientes - CORREGIDO: sin relación estadoQueja
        $quejasRecientes = Queja::where('id_cliente', $cliente->id_cliente)
            ->orderBy('fecha_creacion', 'desc')
            ->limit(5)
            ->get();

        // Notificaciones no leídas
        $notificaciones = Notificacion::with('resolucion')
            ->where('id_usuario', $user->id)
            ->where('i_leido', false)
            ->orderBy('fecha_notificacion', 'desc')
            ->limit(5)
            ->get();

        return view('cliente.dashboard', compact(
            'stats',
            'resolucionesRecientes',
            'quejasRecientes',
            'notificaciones',
            'cliente',
            'persona'
        ));
    }
}