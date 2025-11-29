<?php
// filepath: app/Http/Controllers/Colaborador/DashboardController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\ColaFirma;
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

        // Estadísticas del colaborador
        $stats = [
            // Resoluciones creadas por el usuario
            'mis_resoluciones' => Resolucion::where('id_usuario', $user->id)->count(),
            'resoluciones_mes' => Resolucion::where('id_usuario', $user->id)
                ->whereMonth('fecha_creacion', now()->month)
                ->count(),

            // Firmas pendientes
            'firmas_pendientes' => ColaFirma::where('id_usuario_firmante', $user->id)
                ->whereHas('estadoFirma', fn($q) => $q->where('nombre_estado', 'Pendiente'))
                ->count(),

            // Notificaciones no leídas
            'notificaciones_pendientes' => Notificacion::where('id_usuario', $user->id)
                ->where('i_leido', false)
                ->count(),

            // Resoluciones firmadas por el usuario
            'resoluciones_firmadas' => Resolucion::where('id_usuario_firma', $user->id)->count(),
        ];

        // Mis resoluciones recientes (últimas 5)
        $misResoluciones = Resolucion::with(['estado', 'tipoResolucion'])
            ->where('id_usuario', $user->id)
            ->orderBy('fecha_creacion', 'desc')
            ->limit(5)
            ->get();

        // Firmas pendientes (urgentes primero)
        $firmasPendientes = ColaFirma::with(['resolucion.tipoResolucion', 'usuarioSolicita.persona', 'estadoFirma'])
            ->where('id_usuario_firmante', $user->id)
            ->whereHas('estadoFirma', fn($q) => $q->where('nombre_estado', 'Pendiente'))
            ->orderByRaw("FIELD(prioridad, 'alta', 'media', 'baja')")
            ->orderBy('fecha_limite', 'asc')
            ->limit(5)
            ->get();

        // Notificaciones recientes no leídas
        $notificaciones = Notificacion::with('resolucion')
            ->where('id_usuario', $user->id)
            ->where('i_leido', false)
            ->orderBy('fecha_notificacion', 'desc')
            ->limit(5)
            ->get();

        // Resoluciones por estado (del usuario)
        $resolucionesPorEstado = Resolucion::select('estado.nombre_estado', DB::raw('count(*) as total'))
            ->join('estado', 'resolucion.id_estado', '=', 'estado.id_estado')
            ->where('resolucion.id_usuario', $user->id)
            ->groupBy('estado.nombre_estado')
            ->pluck('total', 'nombre_estado');

        // Actividad reciente del usuario
        $actividadReciente = \App\Models\Auditoria::where('id_usuario', $user->id)
            ->orderBy('fecha_accion', 'desc')
            ->limit(10)
            ->get();

        // Permisos del usuario (para mostrar módulos disponibles)
        $permisos = $user->getAllPermissions()->groupBy('modulo.nombre_modulo');

        return view('colaborador.dashboard', compact(
            'stats',
            'misResoluciones',
            'firmasPendientes',
            'notificaciones',
            'resolucionesPorEstado',
            'actividadReciente',
            'permisos'
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