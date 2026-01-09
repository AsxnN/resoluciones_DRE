<?php
// filepath: app/Http/Controllers/Colaborador/MisResolucionesController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Resolucion;
use App\Models\Estado;
use App\Models\TipoResolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MisResolucionesController extends Controller
{
    /**
     * Mostrar solo las resoluciones creadas por el usuario
     * NO REQUIERE PERMISOS - Acceso libre para todos los colaboradores
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Query base: resoluciones creadas por el usuario o donde es firmante
        $query = Resolucion::with([
            'estado',
            'tipoResolucion',
            'usuarioCreador',
            'usuarioFirmante'
        ])->where(function($q) use ($userId) {
            $q->where('id_usuario', $userId)
              ->orWhere('id_usuario_firma', $userId);
        });

        // Filtros
        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('id_tipo_resolucion', $request->tipo);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('num_resolucion', 'like', "%{$search}%")
                  ->orWhere('asunto_resolucion', 'like', "%{$search}%");
            });
        }

        // Filtro por período
        if ($request->filled('periodo')) {
            $periodo = $request->periodo;
            switch ($periodo) {
                case 'hoy':
                    $query->whereDate('fecha_resolucion', today());
                    break;
                case 'semana':
                    $query->whereBetween('fecha_resolucion', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'mes':
                    $query->whereMonth('fecha_resolucion', now()->month)
                          ->whereYear('fecha_resolucion', now()->year);
                    break;
                case 'trimestre':
                    $query->whereBetween('fecha_resolucion', [now()->startOfQuarter(), now()->endOfQuarter()]);
                    break;
                case 'año':
                    $query->whereYear('fecha_resolucion', now()->year);
                    break;
            }
        }

        // Filtros de rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_resolucion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_resolucion', '<=', $request->fecha_hasta);
        }

        $resoluciones = $query->orderBy('fecha_resolucion', 'desc')
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Estadísticas del usuario
        $stats = [
            'total' => Resolucion::where(function($q) use ($userId) {
                $q->where('id_usuario', $userId)
                  ->orWhere('id_usuario_firma', $userId);
            })->count(),
            
            'borradores' => Resolucion::where('id_usuario', $userId)
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Borrador'))
                ->count(),
            
            'pendientes_firma' => Resolucion::where('id_usuario', $userId)
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Pendiente de Firma'))
                ->count(),
            
            'firmadas' => Resolucion::where(function($q) use ($userId) {
                $q->where('id_usuario', $userId)
                  ->orWhere('id_usuario_firma', $userId);
            })
                ->whereNotNull('archivo_firmado')
                ->whereNotNull('fecha_firma')
                ->count(),
            
            'publicadas' => Resolucion::where('id_usuario', $userId)
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Publicada'))
                ->count(),
            
            'revision' => Resolucion::where('id_usuario', $userId)
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'En Revisión'))
                ->count(),
            
            'mes_actual' => Resolucion::where('id_usuario', $userId)
                ->whereMonth('fecha_resolucion', now()->month)
                ->whereYear('fecha_resolucion', now()->year)
                ->count(),
        ];

        // CAMBIO: Sin filtro i_active
        $estados = Estado::orderBy('nombre_estado')->get();
        $tipos = TipoResolucion::where('i_active', true)->orderBy('nombre_tipo_resolucion')->get();

        return view('colaborador.mis-resoluciones.index', compact('resoluciones', 'stats', 'estados', 'tipos'));
    }

    /**
     * Mostrar detalle de una resolución del usuario
     */
    public function show(Resolucion $resolucion)
    {
        $userId = Auth::id();
        
        // Verificar que el usuario tenga acceso a esta resolución
        if ($resolucion->id_usuario !== $userId && $resolucion->id_usuario_firma !== $userId) {
            abort(403, 'No tiene acceso a esta resolución');
        }

        $resolucion->load([
            'estado',
            'tipoResolucion',
            'usuarioCreador',
            'usuarioFirmante',
            'personas'
        ]);

        return view('colaborador.mis-resoluciones.show', compact('resolucion'));
    }

    /**
     * Obtener estadísticas en tiempo real (AJAX)
     */
    public function estadisticas()
    {
        $userId = Auth::id();

        return response()->json([
            'total' => Resolucion::where('id_usuario', $userId)->count(),
            'borradores' => Resolucion::where('id_usuario', $userId)
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Borrador'))
                ->count(),
            'firmadas' => Resolucion::where('id_usuario_firma', $userId)
                ->whereNotNull('archivo_firmado')
                ->count(),
            'publicadas' => Resolucion::where('id_usuario', $userId)
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Publicada'))
                ->count(),
        ]);
    }
}