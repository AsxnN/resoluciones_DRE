<?php
// filepath: app/Http/Controllers/Cliente/MisResolucionesController.php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Persona;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MisResolucionesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Resoluciones que se le hayan entregado realmente a este cliente
        $query = Resolucion::with(['estado', 'tipoResolucion'])
            ->whereHas('entregas.personaEntrega.user', function($q) use ($user) {
                $q->where('id', $user->id);
            })
            ->whereNotNull('archivo_firmado'); // Solo resoluciones firmadas

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

        // Verificar que esta resolución se le haya entregado realmente a este cliente
        $entrega = $resolucion->entregas()
            ->whereHas('personaEntrega.user', fn($q) => $q->where('id', $user->id))
            ->latest('fecha_entrega')
            ->first();

        if (!$entrega) {
            abort(403, 'No tiene acceso a esta resolución');
        }

        $resolucion->load(['estado', 'tipoResolucion', 'usuarioCreador']);

        return view('cliente.resoluciones.show', compact('resolucion', 'entrega'));
    }

    public function descargar(Resolucion $resolucion)
    {
        $user = Auth::user();

        // Verificar que esta resolución se le haya entregado realmente a este cliente
        $tieneAcceso = $resolucion->entregas()
            ->whereHas('personaEntrega.user', fn($q) => $q->where('id', $user->id))
            ->exists();

        if (!$tieneAcceso) {
            abort(403, 'No tiene acceso a esta resolución');
        }

        // Descargar archivo firmado (las resoluciones del cliente siempre están firmadas)
        $archivo = $resolucion->archivo_firmado;

        if (!$archivo || !Storage::disk('public')->exists($archivo)) {
            abort(404, 'Archivo no encontrado');
        }

        // Registrar descarga en auditoría (opcional)
        \App\Helpers\AuditoriaHelper::registrar(
            'Descarga de resolución',
            'resolucion',
            $resolucion->id_resolucion,
            null,
            ['num_resolucion' => $resolucion->num_resolucion]
        );

        return Storage::disk('public')->download($archivo, $resolucion->num_resolucion . '.pdf');
    }

}