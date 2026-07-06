<?php
// filepath: app/Http/Controllers/Colaborador/DashboardController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Estadísticas generales y del colaborador
        $stats = [
            // Resoluciones creadas por el usuario
            'resoluciones_creadas' => Resolucion::where('id_usuario', $user->id)->count(),
            'resoluciones_mes' => Resolucion::where('id_usuario', $user->id)
                ->whereMonth('fecha_creacion', now()->month)
                ->count(),

            // Resoluciones del sistema que todavía no se han firmado
            'resoluciones_sin_firmar' => Resolucion::whereNull('archivo_firmado')->count(),

            // Resoluciones donde el usuario está relacionado como persona interna
            // (no las creó, pero está involucrado/mencionado en el trámite)
            'resoluciones_relacionadas' => Resolucion::whereHas('personasRelacionadas', function ($q) use ($user) {
                $q->where('persona_resolucion_datos.id_user', $user->id)
                  ->where('persona_resolucion_datos.es_interna', true);
            })->count(),

            // Notificaciones no leídas
            'notificaciones_pendientes' => Notificacion::where('id_usuario', $user->id)
                ->where('i_leido', false)
                ->count(),

            // Resoluciones firmadas por el usuario
            'resoluciones_firmadas' => Resolucion::where('id_usuario_firma', $user->id)->count(),

            // Estadísticas globales (para el dashboard)
            'personas_registradas' => \App\Models\Persona::count(),
            'areas_activas' => \App\Models\Area::where('i_active', true)->count(),
        ];

        // Mis resoluciones recientes (últimas 5) - Cambiado para coincidir con la vista
        $resoluciones_recientes = Resolucion::with(['estado', 'tipoResolucion'])
            ->where('id_usuario', $user->id)
            ->orderBy('fecha_creacion', 'desc')
            ->limit(5)
            ->get();

        // Notificaciones recientes no leídas
        $notificaciones = Notificacion::with('resolucion')
            ->where('id_usuario', $user->id)
            ->where('i_leido', false)
            ->orderBy('fecha_notificacion', 'desc')
            ->limit(5)
            ->get();

        // Actividad reciente del usuario - Cambiado para coincidir con la vista
        $actividades = \App\Models\Auditoria::where('id_usuario', $user->id)
            ->orderBy('fecha_accion', 'desc')
            ->limit(10)
            ->get();

        return view('colaborador.dashboard', compact(
            'stats',
            'resoluciones_recientes',
            'notificaciones',
            'actividades'
        ));
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarNotificacionLeida(Notificacion $notificacion)
    {
        if ($notificacion->id_usuario !== Auth::id()) {
            abort(403);
        }

        $notificacion->update([
            'i_leido' => true,
            'fecha_lectura' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas()
    {
        Notificacion::where('id_usuario', Auth::id())
            ->where('i_leido', false)
            ->update([
                'i_leido' => true,
                'fecha_lectura' => now(),
            ]);

        return redirect()->back()->with('success', '✅ Todas las notificaciones marcadas como leídas');
    }
}