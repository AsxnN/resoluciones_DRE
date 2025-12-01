<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Auditoria::with('usuario.persona')
            ->orderBy('fecha_accion', 'desc');

        // Filtros
        if ($request->filled('usuario_id')) {
            $query->where('id_usuario', $request->usuario_id);
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_accion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_accion', '<=', $request->fecha_hasta);
        }

        if ($request->filled('buscar')) {
            $query->where('descripcion', 'like', '%' . $request->buscar . '%');
        }

        $auditorias = $query->paginate(50);

        // Estadísticas (sin usar columnas que no existen)
        $estadisticas = [
            'total' => Auditoria::count(),
            'hoy' => Auditoria::whereDate('fecha_accion', today())->count(),
            'semana' => Auditoria::where('fecha_accion', '>=', now()->subDays(7))->count(),
            'criticos' => 0, // No hay columna nivel
            'por_nivel' => [],
            'por_modulo' => [],
        ];

        $usuarios = User::with('persona')
            ->where('i_active', true)
            ->orderBy('name')
            ->get();

        $modulos = []; // No hay columna modulo

        return view('admin.auditoria.index', compact(
            'auditorias',
            'estadisticas',
            'usuarios',
            'modulos'
        ));
    }

    public function show($id)
    {
        $auditoria = Auditoria::with('usuario.persona')
            ->where('id_auditoria', $id)
            ->firstOrFail();
        
        return view('admin.auditoria.show', compact('auditoria'));
    }

    public function exportar(Request $request)
    {
        $query = Auditoria::with('usuario')
            ->orderBy('fecha_accion', 'desc');

        if ($request->filled('usuario_id')) {
            $query->where('id_usuario', $request->usuario_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_accion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_accion', '<=', $request->fecha_hasta);
        }

        $auditorias = $query->get();
        $filename = 'auditoria_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($auditorias) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['ID', 'Fecha/Hora', 'Usuario', 'Acción', 'Descripción', 'IP']);

            foreach ($auditorias as $auditoria) {
                fputcsv($file, [
                    $auditoria->id_auditoria,
                    $auditoria->fecha_accion->format('Y-m-d H:i:s'),
                    $auditoria->usuario ? $auditoria->usuario->name : 'Sistema',
                    $auditoria->accion,
                    $auditoria->descripcion,
                    $auditoria->ip_address,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function limpiar(Request $request)
    {
        $request->validate([
            'dias' => 'required|integer|min:30|max:365',
        ]);

        $fecha = now()->subDays($request->dias);
        $eliminados = Auditoria::where('fecha_accion', '<', $fecha)->delete();

        return back()->with('success', "Se eliminaron {$eliminados} registros anteriores a {$fecha->format('d/m/Y')}");
    }
}