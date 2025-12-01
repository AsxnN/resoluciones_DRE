<?php
// filepath: app/Http/Controllers/Colaborador/ResolucionController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Persona;
use App\Models\Resolucion;
use App\Models\TipoResolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ResolucionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:resoluciones.ver', only: ['index', 'show']),
            new Middleware('permission:resoluciones.crear', only: ['create', 'store']),
            new Middleware('permission:resoluciones.editar', only: ['edit', 'update']),
            new Middleware('permission:resoluciones.eliminar', only: ['destroy', 'toggleEstado']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Resolucion::with(['estado', 'tipoResolucion', 'usuarioCreador.persona']);

        // Si no tiene permiso de ver todas, solo ver las propias
        if (!Auth::user()->can('ver_todas_resoluciones')) {
            $query->where('id_usuario', Auth::id());
        }

        // Filtros
        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
        }

        if ($request->filled('tipo_resolucion')) {
            $query->where('id_tipo_resolucion', $request->tipo_resolucion);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('num_resolucion', 'like', "%{$search}%")
                  ->orWhere('asunto_resolucion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_resolucion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_resolucion', '<=', $request->fecha_hasta);
        }

        if ($request->filled('firmadas')) {
            if ($request->boolean('firmadas')) {
                $query->whereNotNull('archivo_firmado');
            } else {
                $query->whereNull('archivo_firmado');
            }
        }

        $resoluciones = $query->orderBy('fecha_resolucion', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Datos para filtros
        $estados = Estado::all();
        $tiposResolucion = TipoResolucion::where('i_active', true)->get();

        return view('colaborador.resoluciones.index', compact('resoluciones', 'estados', 'tiposResolucion'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $estados = Estado::all();
        $tiposResolucion = TipoResolucion::where('i_active', true)->get();

        return view('colaborador.resoluciones.create', compact('estados', 'tiposResolucion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tipo_resolucion' => 'required|exists:tipo_resolucion,id_tipo_resolucion',
            'num_resolucion' => 'required|string|max:50|unique:resolucion,num_resolucion',
            'fecha_resolucion' => 'required|date',
            'visto_resolucion' => 'required|string',
            'asunto_resolucion' => 'required|string|max:500',
            'archivo_resolucion' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'personas_involucradas' => 'nullable|array',
            'personas_involucradas.*' => 'exists:persona,id_persona',
        ]);

        DB::beginTransaction();
        try {
            // Estado borrador por defecto
            $estadoBorrador = Estado::where('nombre_estado', 'Borrador')->first();
            $validated['id_estado'] = $estadoBorrador?->id_estado;
            $validated['id_usuario'] = Auth::id();

            // Subir archivo si existe
            if ($request->hasFile('archivo_resolucion')) {
                $archivo = $request->file('archivo_resolucion');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $path = $archivo->storeAs('resoluciones', $nombreArchivo, 'public');
                $validated['archivo_resolucion'] = $path;
            }

            $resolucion = Resolucion::create($validated);

            // Asociar personas involucradas
            if ($request->filled('personas_involucradas')) {
                $resolucion->personasInvolucradas()->sync($request->personas_involucradas);
            }

            DB::commit();

            return redirect()
                ->route('colaborador.resoluciones.show', $resolucion)
                ->with('success', '✅ Resolución creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Eliminar archivo si se subió
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error al crear resolución: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Resolucion $resolucion)
    {
        // Verificar permisos
        if (!Auth::user()->can('ver_todas_resoluciones') && $resolucion->id_usuario !== Auth::id()) {
            abort(403);
        }

        $resolucion->load([
            'estado',
            'tipoResolucion',
            'usuarioCreador.persona',
            'usuarioFirmante.persona',
            'personasInvolucradas',
            'colaFirmas.estadoFirma',
            'colaFirmas.usuarioFirmante.persona',
            'historialFirmas.usuario.persona',
            'notificaciones' => fn($q) => $q->latest()->limit(5),
        ]);

        return view('colaborador.resoluciones.show', compact('resolucion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resolucion $resolucion)
    {
        // Solo puede editar si es el creador o tiene permiso
        if ($resolucion->id_usuario !== Auth::id() && !Auth::user()->can('editar_todas_resoluciones')) {
            abort(403);
        }

        // No permitir editar si está firmada
        if ($resolucion->archivo_firmado) {
            return redirect()
                ->back()
                ->with('error', '❌ No se puede editar una resolución firmada');
        }

        $estados = Estado::all();
        $tiposResolucion = TipoResolucion::where('i_active', true)->get();
        $personasInvolucradas = $resolucion->personasInvolucradas->pluck('id_persona')->toArray();

        return view('colaborador.resoluciones.edit', compact('resolucion', 'estados', 'tiposResolucion', 'personasInvolucradas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resolucion $resolucion)
    {
        // Verificar permisos
        if ($resolucion->id_usuario !== Auth::id() && !Auth::user()->can('editar_todas_resoluciones')) {
            abort(403);
        }

        // No permitir editar si está firmada
        if ($resolucion->archivo_firmado) {
            return redirect()
                ->back()
                ->with('error', '❌ No se puede editar una resolución firmada');
        }

        $validated = $request->validate([
            'id_estado' => 'required|exists:estado,id_estado',
            'id_tipo_resolucion' => 'required|exists:tipo_resolucion,id_tipo_resolucion',
            'num_resolucion' => 'required|string|max:50|unique:resolucion,num_resolucion,' . $resolucion->id_resolucion . ',id_resolucion',
            'fecha_resolucion' => 'required|date',
            'visto_resolucion' => 'required|string',
            'asunto_resolucion' => 'required|string|max:500',
            'archivo_resolucion' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'personas_involucradas' => 'nullable|array',
            'personas_involucradas.*' => 'exists:persona,id_persona',
        ]);

        DB::beginTransaction();
        try {
            // Subir nuevo archivo si existe
            if ($request->hasFile('archivo_resolucion')) {
                // Eliminar archivo anterior
                if ($resolucion->archivo_resolucion) {
                    Storage::disk('public')->delete($resolucion->archivo_resolucion);
                }

                $archivo = $request->file('archivo_resolucion');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $path = $archivo->storeAs('resoluciones', $nombreArchivo, 'public');
                $validated['archivo_resolucion'] = $path;
            }

            $resolucion->update($validated);

            // Actualizar personas involucradas
            if ($request->has('personas_involucradas')) {
                $resolucion->personasInvolucradas()->sync($request->personas_involucradas ?? []);
            }

            DB::commit();

            return redirect()
                ->route('colaborador.resoluciones.show', $resolucion)
                ->with('success', '✅ Resolución actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar resolución: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resolucion $resolucion)
    {
        // Solo puede eliminar si es el creador o tiene permiso
        if ($resolucion->id_usuario !== Auth::id() && !Auth::user()->can('eliminar_todas_resoluciones')) {
            abort(403);
        }

        // No permitir eliminar si está firmada
        if ($resolucion->archivo_firmado) {
            return redirect()
                ->back()
                ->with('error', '❌ No se puede eliminar una resolución firmada');
        }

        $resolucion->delete();

        return redirect()
            ->route('colaborador.resoluciones.index')
            ->with('success', '✅ Resolución eliminada exitosamente');
    }

    /**
     * Descargar archivo de resolución
     */
    public function descargar(Resolucion $resolucion)
    {
        if (!$resolucion->archivo_resolucion || !Storage::disk('public')->exists($resolucion->archivo_resolucion)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('public')->download(
            $resolucion->archivo_resolucion,
            $resolucion->num_resolucion . '.pdf'
        );
    }

    /**
     * Descargar archivo firmado
     */
    public function descargarFirmado(Resolucion $resolucion)
    {
        if (!$resolucion->archivo_firmado || !Storage::disk('public')->exists($resolucion->archivo_firmado)) {
            abort(404, 'Archivo firmado no encontrado');
        }

        return Storage::disk('public')->download(
            $resolucion->archivo_firmado,
            $resolucion->num_resolucion . '_firmado.pdf'
        );
    }

    /**
     * Cambiar estado de resolución
     */
    public function cambiarEstado(Request $request, Resolucion $resolucion)
    {
        $request->validate([
            'id_estado' => 'required|exists:estado,id_estado',
        ]);

        // Verificar permisos
        if ($resolucion->id_usuario !== Auth::id() && !Auth::user()->can('cambiar_estado_resoluciones')) {
            abort(403);
        }

        $resolucion->update(['id_estado' => $request->id_estado]);

        return redirect()
            ->back()
            ->with('success', '✅ Estado cambiado exitosamente');
    }

    /**
     * Generar número de resolución automático
     */
    public function generarNumero(Request $request)
    {
        $request->validate([
            'tipo_resolucion_id' => 'required|exists:tipo_resolucion,id_tipo_resolucion',
        ]);

        $tipoResolucion = TipoResolucion::findOrFail($request->tipo_resolucion_id);
        $anio = now()->year;

        // Obtener último número del año
        $ultimaResolucion = Resolucion::where('id_tipo_resolucion', $tipoResolucion->id_tipo_resolucion)
            ->whereYear('fecha_resolucion', $anio)
            ->orderBy('num_resolucion', 'desc')
            ->first();

        $numero = 1;
        if ($ultimaResolucion && preg_match('/-(\d+)-/', $ultimaResolucion->num_resolucion, $matches)) {
            $numero = intval($matches[1]) + 1;
        }

        // Formato: RD-0001-2025
        $prefijo = strtoupper(substr($tipoResolucion->nombre_tipo_resolucion, 0, 2));
        $numResolucion = sprintf('%s-%04d-%d', $prefijo, $numero, $anio);

        return response()->json([
            'success' => true,
            'num_resolucion' => $numResolucion,
        ]);
    }
}