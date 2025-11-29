<?php
// filepath: app/Http/Controllers/Colaborador/MisResolucionesController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MisResolucionesController extends Controller
{
    /**
     * Mostrar solo las resoluciones creadas por el usuario
     */
    public function index(Request $request)
    {
        $query = Resolucion::with(['estado', 'tipoResolucion'])
            ->where('id_usuario', Auth::id());

        // Filtros
        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('num_resolucion', 'like', "%{$search}%")
                  ->orWhere('asunto_resolucion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_resolucion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_resolucion', '<=', $request->fecha_hasta);
        }

        $resoluciones = $query->orderBy('fecha_resolucion', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Estadísticas del usuario
        $stats = [
            'total' => Resolucion::where('id_usuario', Auth::id())->count(),
            'borradores' => Resolucion::where('id_usuario', Auth::id())
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Borrador'))
                ->count(),
            'pendientes_firma' => Resolucion::where('id_usuario', Auth::id())
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Pendiente de Firma'))
                ->count(),
            'firmadas' => Resolucion::where('id_usuario', Auth::id())
                ->whereNotNull('archivo_firmado')
                ->count(),
            'publicadas' => Resolucion::where('id_usuario', Auth::id())
                ->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Publicada'))
                ->count(),
        ];

        $estados = \App\Models\Estado::all();

        return view('colaborador.mis-resoluciones.index', compact('resoluciones', 'stats', 'estados'));
    }
}