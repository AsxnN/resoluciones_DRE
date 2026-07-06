<?php
// filepath: app/Http/Controllers/Colaborador/ReporteController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Reporte de personas con más resoluciones
     */
    public function personasConMasResoluciones(Request $request)
    {
        // Obtener parámetros de filtro
        $limite = max(1, min(500, (int) $request->input('limite', 10)));
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $tipoRelacion = $request->input('tipo_relacion'); // involucrado, notificado, firmante

        // Query base - personas con sus resoluciones
        $query = Persona::select(
                'persona.id_persona',
                'persona.nombre_persona',
                'persona.apellido_paterno',
                'persona.apellido_materno',
                'persona.correo',
                'persona.telefono',
                DB::raw('COUNT(DISTINCT persona_resolucion.id_resolucion) as total_resoluciones')
            )
            ->join('persona_resolucion', 'persona.id_persona', '=', 'persona_resolucion.id_persona')
            ->join('resolucion', 'persona_resolucion.id_resolucion', '=', 'resolucion.id_resolucion')
            ->where('persona.i_active', true)
            ->where('persona_resolucion.i_active', true);

        // Filtro por fechas
        if ($fechaDesde) {
            $query->where('resolucion.fecha_resolucion', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->where('resolucion.fecha_resolucion', '<=', $fechaHasta);
        }

        // Filtro por tipo de relación
        if ($tipoRelacion) {
            $query->where('persona_resolucion.tipo_relacion', $tipoRelacion);
        }

        $personas = $query->groupBy(
                'persona.id_persona',
                'persona.nombre_persona',
                'persona.apellido_paterno',
                'persona.apellido_materno',
                'persona.correo',
                'persona.telefono'
            )
            ->orderBy('total_resoluciones', 'desc')
            ->limit($limite)
            ->get();

        // Agregar nombre completo
        $personas->each(function($persona) {
            $persona->nombre_completo = trim($persona->nombre_persona . ' ' . 
                                              $persona->apellido_paterno . ' ' . 
                                              $persona->apellido_materno);
        });

        // Estadísticas generales
        $estadisticas = [
            'total_personas_activas' => Persona::where('i_active', true)->count(),
            'total_resoluciones' => Resolucion::count(),
            'personas_con_resoluciones' => DB::table('persona_resolucion')
                ->distinct('id_persona')
                ->where('i_active', true)
                ->count('id_persona'),
            'promedio_resoluciones_por_persona' => round(
                DB::table('persona_resolucion')
                    ->where('i_active', true)
                    ->count() / 
                DB::table('persona_resolucion')
                    ->distinct('id_persona')
                    ->where('i_active', true)
                    ->count('id_persona'),
                2
            ),
        ];

        // Datos para gráfica (Top 10)
        $datosGrafica = $personas->take(10)->map(function($persona) {
            return [
                'nombre' => $persona->nombre_completo,
                'total' => $persona->total_resoluciones,
            ];
        });

        return view('colaborador.reportes.personas-resoluciones', compact(
            'personas',
            'estadisticas',
            'datosGrafica',
            'limite',
            'fechaDesde',
            'fechaHasta',
            'tipoRelacion'
        ));
    }

    /**
     * Exportar reporte a Excel
     */
    public function exportarPersonasResoluciones(Request $request)
    {
        // Similar query pero sin límite
        $query = Persona::select(
                'persona.nombre_persona',
                'persona.apellido_paterno',
                'persona.apellido_materno',
                'persona.correo',
                'persona.telefono',
                DB::raw('COUNT(DISTINCT persona_resolucion.id_resolucion) as total_resoluciones')
            )
            ->join('persona_resolucion', 'persona.id_persona', '=', 'persona_resolucion.id_persona')
            ->join('resolucion', 'persona_resolucion.id_resolucion', '=', 'resolucion.id_resolucion')
            ->where('persona.i_active', true)
            ->where('persona_resolucion.i_active', true);

        if ($request->fecha_desde) {
            $query->where('resolucion.fecha_resolucion', '>=', $request->fecha_desde);
        }
        if ($request->fecha_hasta) {
            $query->where('resolucion.fecha_resolucion', '<=', $request->fecha_hasta);
        }
        if ($request->tipo_relacion) {
            $query->where('persona_resolucion.tipo_relacion', $request->tipo_relacion);
        }

        $personas = $query->groupBy(
                'persona.nombre_persona',
                'persona.apellido_paterno',
                'persona.apellido_materno',
                'persona.correo',
                'persona.telefono'
            )
            ->orderBy('total_resoluciones', 'desc')
            ->get();

        // Generar CSV
        $filename = 'reporte_personas_resoluciones_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($personas) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($file, [
                'Nombre Completo',
                'Correo',
                'Teléfono',
                'Total Resoluciones'
            ]);

            // Datos
            foreach ($personas as $persona) {
                fputcsv($file, [
                    trim($persona->nombre_persona . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
                    $persona->correo ?? 'Sin correo',
                    $persona->telefono ?? 'Sin teléfono',
                    $persona->total_resoluciones,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}