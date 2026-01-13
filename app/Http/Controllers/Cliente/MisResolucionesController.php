<?php
// filepath: app/Http/Controllers/Cliente/MisResolucionesController.php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MisResolucionesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            abort(403);
        }

        $persona = $cliente->persona;

        $query = Resolucion::with(['estado', 'tipoResolucion'])
            ->whereHas('personasInvolucradas', function($q) use ($persona) {
                $q->where('persona.id_persona', $persona->id_persona);
            });

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('num_resolucion', 'like', "%{$search}%")
                  ->orWhere('asunto_resolucion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
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

        $estados = \App\Models\Estado::all();

        return view('cliente.resoluciones.index', compact('resoluciones', 'estados'));
    }

    public function buscar(Request $request)
    {
        $query = Resolucion::with(['estado', 'tipoResolucion', 'personasInvolucradas.persona'])
            ->whereNotNull('archivo_firmado'); // Solo mostrar resoluciones firmadas públicamente

        // Búsqueda por número de resolución
        if ($request->filled('numero')) {
            $query->where('num_resolucion', 'like', '%' . $request->numero . '%');
        }

        // Búsqueda por DNI
        if ($request->filled('dni')) {
            $query->whereHas('personasInvolucradas', function($q) use ($request) {
                $q->where('persona.num_documento', $request->dni);
            });
        }

        // Búsqueda por nombre
        if ($request->filled('nombre')) {
            $search = $request->nombre;
            $query->whereHas('personasInvolucradas', function($q) use ($search) {
                $q->where(function($subQuery) use ($search) {
                    $subQuery->where('persona.nombres', 'like', "%{$search}%")
                             ->orWhere('persona.apellido_paterno', 'like', "%{$search}%")
                             ->orWhere('persona.apellido_materno', 'like', "%{$search}%");
                });
            });
        }

        $resoluciones = $query->orderBy('fecha_resolucion', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('cliente.busqueda.index', compact('resoluciones'));
    }

    public function show(Resolucion $resolucion)
    {
        $user = Auth::user();
        $cliente = $user->cliente;
        $persona = $cliente->persona;

        // Verificar que el cliente esté involucrado
        if (!$resolucion->personasInvolucradas->contains('id_persona', $persona->id_persona)) {
            abort(403, 'No tiene acceso a esta resolución');
        }

        $resolucion->load([
            'estado',
            'tipoResolucion',
            'usuarioCreador.persona',
            'personasInvolucradas',
        ]);

        return view('cliente.resoluciones.show', compact('resolucion'));
    }

    public function descargar(Resolucion $resolucion)
    {
        $user = Auth::user();
        $cliente = $user->cliente;
        $persona = $cliente->persona;

        if (!$resolucion->personasInvolucradas->contains('id_persona', $persona->id_persona)) {
            abort(403);
        }

        // Descargar archivo firmado si existe, sino el original
        $archivo = $resolucion->archivo_firmado ?? $resolucion->archivo_resolucion;

        if (!$archivo || !Storage::disk('public')->exists($archivo)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('public')->download($archivo, $resolucion->num_resolucion . '.pdf');
    }
}