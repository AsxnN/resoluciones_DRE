<?php
// filepath: app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\Resolucion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_usuarios' => User::count(),
            'usuarios_activos' => User::where('i_active', true)->count(),
            'total_resoluciones' => Resolucion::count(),
            'resoluciones_mes' => Resolucion::whereMonth('fecha_creacion', now()->month)->count(),
            'resoluciones_firmadas' => Resolucion::firmadas()->count(),
            'resoluciones_pendientes_firma' => Resolucion::pendientesFirma()->count(),
            'usuarios_colaboradores' => User::where('tipo_acceso', 'colaborador')->count(),
            'usuarios_clientes' => User::where('tipo_acceso', 'cliente')->count(),
        ];

        // Resoluciones por estado
        $resolucionesPorEstado = Resolucion::select('estado.nombre_estado', DB::raw('count(*) as total'))
            ->join('estado', 'resolucion.id_estado', '=', 'estado.id_estado')
            ->groupBy('estado.nombre_estado')
            ->pluck('total', 'nombre_estado');

        // Resoluciones por tipo
        $resolucionesPorTipo = Resolucion::select('tipo_resolucion.nombre_tipo_resolucion', DB::raw('count(*) as total'))
            ->join('tipo_resolucion', 'resolucion.id_tipo_resolucion', '=', 'tipo_resolucion.id_tipo_resolucion')
            ->groupBy('tipo_resolucion.nombre_tipo_resolucion')
            ->pluck('total', 'nombre_tipo_resolucion');

        // Actividad reciente (últimas 10 acciones)
        $actividadReciente = Auditoria::with('usuario.persona')
            ->orderBy('fecha_accion', 'desc')
            ->limit(10)
            ->get();

        // Resoluciones recientes
        $resolucionesRecientes = Resolucion::with(['estado', 'tipoResolucion', 'usuarioCreador.persona'])
            ->orderBy('fecha_creacion', 'desc')
            ->limit(5)
            ->get();

        // Usuarios registrados por mes (últimos 6 meses)
        $usuariosPorMes = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        return view('admin.dashboard', compact(
            'stats',
            'resolucionesPorEstado',
            'resolucionesPorTipo',
            'actividadReciente',
            'resolucionesRecientes',
            'usuariosPorMes'
        ));
    }
}