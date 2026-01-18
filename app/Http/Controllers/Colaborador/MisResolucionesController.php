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
     * Mostrar solo las resoluciones creadas por el usuario o donde está involucrado
     * NO REQUIERE PERMISOS - Acceso libre para todos los colaboradores
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $personaId = $user->id_persona; // ID de la persona asociada al usuario
        
        // Query base: resoluciones donde el usuario está relacionado
        $query = Resolucion::with([
            'estado',
            'tipoResolucion',
            'usuarioCreador',
            'usuarioFirmante'
        ])->where(function($q) use ($userId, $personaId) {
            // 1. Resoluciones creadas por el usuario
            $q->where('id_usuario', $userId)
            // 2. Resoluciones firmadas por el usuario
            ->orWhere('id_usuario_firma', $userId);
            
            // 3. Resoluciones donde la persona está relacionada en persona_resolucion (tabla antigua)
            if ($personaId) {
                $q->orWhereHas('personas', function($query) use ($personaId) {
                    $query->where('persona.id_persona', $personaId)
                        ->where('persona_resolucion.i_active', true);
                });
            }
            
            // 4. ← AGREGAR: Resoluciones donde el usuario está relacionado en persona_resolucion_datos
            $q->orWhereHas('personasRelacionadas', function($query) use ($userId) {
                $query->where('persona_resolucion_datos.id_user', $userId)
                    ->where('persona_resolucion_datos.es_interna', true);
            });
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

        // Filtro por tipo de relación (opcional)
        if ($request->filled('relacion') && $personaId) {
            $relacion = $request->relacion;
            $query->whereHas('personas', function($q) use ($personaId, $relacion) {
                $q->where('persona.id_persona', $personaId)
                  ->where('persona_resolucion.tipo_relacion', $relacion);
            });
        }

        $resoluciones = $query->orderBy('fecha_resolucion', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Datos para filtros
        $estados = Estado::all();
        $tipos = TipoResolucion::where('i_active', true)->get();

        // Estadísticas
        $stats = $this->obtenerEstadisticas($userId, $personaId);

        return view('colaborador.mis-resoluciones.index', compact('resoluciones', 'estados', 'tipos', 'stats'));
    }

    /**
     * Mostrar detalles de una resolución
     */
    public function show(Resolucion $resolucion)
    {
        $userId = Auth::id();
        $personaId = Auth::user()->id_persona;

        // Verificar que el usuario tenga acceso a esta resolución
        $tieneAcceso = $resolucion->id_usuario === $userId
            || $resolucion->id_usuario_firma === $userId
            || ($personaId && $resolucion->personas()->where('persona.id_persona', $personaId)->exists());

        if (!$tieneAcceso) {
            abort(403, 'No tiene acceso a esta resolución');
        }

        $resolucion->load([
            'estado',
            'tipoResolucion',
            'usuarioCreador.persona',
            'usuarioFirmante.persona',
            'personas',
            'colaFirmas.estadoFirma',
            'historialFirmas.usuario.persona',
        ]);

        return view('colaborador.mis-resoluciones.show', compact('resolucion'));
    }

    /**
     * Obtener estadísticas del usuario
     */
    private function obtenerEstadisticas($userId, $personaId)
    {
        $baseQuery = function() use ($userId, $personaId) {
            return Resolucion::where(function($q) use ($userId, $personaId) {
                $q->where('id_usuario', $userId)
                  ->orWhere('id_usuario_firma', $userId);
                
                if ($personaId) {
                    $q->orWhereHas('personas', function($query) use ($personaId) {
                        $query->where('persona.id_persona', $personaId)
                              ->where('persona_resolucion.i_active', true);
                    });
                }
            });
        };

        return [
            'total' => $baseQuery()->count(),
            'creadas' => Resolucion::where('id_usuario', $userId)->count(),
            'involucrado' => $personaId 
                ? Resolucion::whereHas('personas', function($q) use ($personaId) {
                    $q->where('persona.id_persona', $personaId)
                      ->where('persona_resolucion.tipo_relacion', 'involucrado');
                })->count() 
                : 0,
            'firmadas' => Resolucion::where('id_usuario_firma', $userId)->count(),
            'pendientes' => $baseQuery()->whereHas('estado', function($q) {
                $q->where('nombre_estado', 'Pendiente');
            })->count(),
            'este_mes' => $baseQuery()->whereMonth('fecha_resolucion', now()->month)
                                      ->whereYear('fecha_resolucion', now()->year)
                                      ->count(),
        ];
    }

    /**
     * Estadísticas en formato JSON (para gráficos)
     */
    public function estadisticas(Request $request)
    {
        $userId = Auth::id();
        $personaId = Auth::user()->id_persona;

        // Resoluciones por mes (últimos 6 meses)
        $porMes = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $count = Resolucion::where(function($q) use ($userId, $personaId) {
                $q->where('id_usuario', $userId)
                  ->orWhere('id_usuario_firma', $userId);
                
                if ($personaId) {
                    $q->orWhereHas('personas', function($query) use ($personaId) {
                        $query->where('persona.id_persona', $personaId);
                    });
                }
            })->whereMonth('fecha_resolucion', $fecha->month)
              ->whereYear('fecha_resolucion', $fecha->year)
              ->count();

            $porMes[] = [
                'mes' => $fecha->format('M Y'),
                'cantidad' => $count
            ];
        }

        // Resoluciones por estado
        $porEstado = Estado::withCount(['resoluciones' => function($query) use ($userId, $personaId) {
            $query->where(function($q) use ($userId, $personaId) {
                $q->where('id_usuario', $userId)
                  ->orWhere('id_usuario_firma', $userId);
                
                if ($personaId) {
                    $q->orWhereHas('personas', function($query) use ($personaId) {
                        $query->where('persona.id_persona', $personaId);
                    });
                }
            });
        }])->get()->map(function($estado) {
            return [
                'estado' => $estado->nombre_estado,
                'cantidad' => $estado->resoluciones_count,
                'color' => $this->getEstadoColor($estado->nombre_estado),
            ];
        });

        return response()->json([
            'por_mes' => $porMes,
            'por_estado' => $porEstado,
        ]);
    }

    private function getEstadoColor($nombreEstado)
    {
        $colores = [
            'Aprobado' => '#10B981',
            'Pendiente' => '#F59E0B',
            'Rechazado' => '#EF4444',
            'Borrador' => '#6B7280',
            'En Proceso' => '#3B82F6',
        ];

        return $colores[$nombreEstado] ?? '#6B7280';
    }
}