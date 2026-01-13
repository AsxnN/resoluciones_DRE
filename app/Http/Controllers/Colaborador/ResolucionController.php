<?php
// filepath: app/Http/Controllers/Colaborador/ResolucionController.php

namespace App\Http\Controllers\Colaborador;

use App\Mail\ResolucionNotificacion;
use Illuminate\Support\Facades\Mail;

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
        $personas = Persona::where('i_active', true)
            ->orderBy('apellido_paterno')
            ->get();
        $dependencias = \App\Models\Dependencia::where('i_active', true)
            ->orderBy('nombre_dependencia')
            ->get();

        return view('colaborador.resoluciones.create-paso1', compact('estados', 'tiposResolucion', 'personas', 'dependencias'));
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
        // El middleware ya verificó el permiso 'resoluciones.ver'
        // Ya no necesitamos verificaciones adicionales

        $resolucion->load([
            'estado',
            'tipoResolucion',
            'usuarioCreador.persona',
            'usuarioFirmante.persona',
            'personas',
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

    /**
     * Paso 1: Mostrar formulario para datos básicos y personas relacionadas
     */
    public function createPaso1()
    {
        $estados = Estado::all();
        $tiposResolucion = TipoResolucion::where('i_active', true)->get();
        $personas = Persona::where('i_active', true)
            ->orderBy('apellido_paterno')
            ->get();

        return view('colaborador.resoluciones.create-paso1', compact('estados', 'tiposResolucion', 'personas'));
    }

    /**
     * Guardar datos del paso 1 en sesión y mostrar paso 2
     */
    public function storePaso1(Request $request)
    {
        $validated = $request->validate([
            'num_resolucion' => 'required|string|max:50|unique:resolucion,num_resolucion',
            'fecha_resolucion' => 'required|date',
            'id_estado' => 'required|exists:estado,id_estado',
            'id_tipo_resolucion' => 'required|exists:tipo_resolucion,id_tipo_resolucion',
            'id_dependencia' => 'nullable|exists:dependencia,id_dependencias',  // CORREGIDO
            'personas_relacionadas' => 'nullable|array',
            'personas_relacionadas.*.id_persona' => 'required|exists:persona,id_persona',
        ]);

        // Guardar en sesión
        session(['resolucion_paso1' => $validated]);

        return redirect()->route('colaborador.resoluciones.create-paso2');
    }

    /**
     * Paso 2: Mostrar formulario para contenido de la resolución
     */
    public function createPaso2()
    {
        if (!session()->has('resolucion_paso1')) {
            return redirect()->route('colaborador.resoluciones.create')
                ->with('error', 'Debe completar el paso 1 primero');
        }

        $datosPaso1 = session('resolucion_paso1');  // CORREGIDO: estaba como $datossPaso1
        $resoluciones = Resolucion::orderBy('num_resolucion', 'desc')->take(10)->get();

        return view('colaborador.resoluciones.create-paso2', compact('datosPaso1', 'resoluciones'));
    }

    /**
     * Guardar datos del paso 2 en sesión y mostrar paso 3
     */
    public function storePaso2(Request $request)
    {
        if (!session()->has('resolucion_paso1')) {
            return redirect()->route('colaborador.resoluciones.create')
                ->with('error', 'Debe completar el paso 1 primero');
        }

        $validated = $request->validate([
            'id_resolucion_dependiente' => 'nullable|exists:resolucion,id_resolucion',
            'visto_resolucion' => 'required|string',
            'asunto_resolucion' => 'required|string|max:500',
            'archivo_resolucion' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Subir archivo si existe
        if ($request->hasFile('archivo_resolucion')) {
            $archivo = $request->file('archivo_resolucion');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $path = $archivo->storeAs('resoluciones/temp', $nombreArchivo, 'public');
            $validated['archivo_resolucion'] = $path;
        }

        // Guardar en sesión
        session(['resolucion_paso2' => $validated]);

        return redirect()->route('colaborador.resoluciones.create-paso3');
    }

    /**
     * Paso 3: Mostrar resumen y opciones de envío
     */
    public function createPaso3()
    {
        if (!session()->has('resolucion_paso1') || !session()->has('resolucion_paso2')) {
            return redirect()->route('colaborador.resoluciones.create')
                ->with('error', 'Debe completar los pasos anteriores primero');
        }

        $datosPaso1 = session('resolucion_paso1');
        $datosPaso2 = session('resolucion_paso2');

        // DEBUG TEMPORAL
        // dd($datosPaso1, $datosPaso2);

        return view('colaborador.resoluciones.create-paso3', compact('datosPaso1', 'datosPaso2'));
    }

    /**
     * Guardar la resolución completa (Paso 3 final)
        */
    public function storeFinal(Request $request)
    {
        if (!session()->has('resolucion_paso1') || !session()->has('resolucion_paso2')) {
            return redirect()->route('colaborador.resoluciones.create')
                ->with('error', 'Debe completar todos los pasos');
        }

        $validated = $request->validate([
            'enviar_whatsapp' => 'nullable|boolean',
            'enviar_correo' => 'nullable|boolean',
            'aceptar_terminos' => 'required|accepted',
        ]);

        DB::beginTransaction();
        try {
            $paso1 = session('resolucion_paso1');
            $paso2 = session('resolucion_paso2');

            // Mover archivo de temp a ubicación final si existe
            if (isset($paso2['archivo_resolucion'])) {
                $tempPath = $paso2['archivo_resolucion'];
                $finalPath = str_replace('/temp/', '/', $tempPath);
                Storage::disk('public')->move($tempPath, $finalPath);
                $paso2['archivo_resolucion'] = $finalPath;
            }

            // Crear resolución
            $resolucion = Resolucion::create([
                'id_estado' => $paso1['id_estado'],
                'id_tipo_resolucion' => $paso1['id_tipo_resolucion'],
                'id_dependencia' => $paso1['id_dependencia'] ?? null,
                'num_resolucion' => $paso1['num_resolucion'],
                'fecha_resolucion' => $paso1['fecha_resolucion'],
                'visto_resolucion' => $paso2['visto_resolucion'],
                'asunto_resolucion' => $paso2['asunto_resolucion'],
                'archivo_resolucion' => $paso2['archivo_resolucion'] ?? null,
                'id_resolucion_dependiente' => $paso2['id_resolucion_dependiente'] ?? null,
                'id_usuario' => Auth::id(),
            ]);

            // Cargar relaciones necesarias para el email
            $resolucion->load(['estado', 'tipoResolucion']);

            // Asociar personas relacionadas y enviar notificaciones
            $correosEnviados = 0;
            $erroresCorreo = 0;
            $personasSinCorreo = 0;

            if (isset($paso1['personas_relacionadas']) && is_array($paso1['personas_relacionadas'])) {
                foreach ($paso1['personas_relacionadas'] as $personaData) {
                    // Asociar persona a la resolución
                    $resolucion->personas()->attach($personaData['id_persona'], [
                        'tipo_relacion' => 'involucrado',
                        'i_active' => true,
                    ]);
                    
                    // Enviar correo si está marcada la opción
                    if ($request->boolean('enviar_correo')) {
                        $persona = Persona::find($personaData['id_persona']);
                        
                        if ($persona) {
                            if ($persona->correo) {
                                try {
                                    Mail::to($persona->correo)->send(new ResolucionNotificacion($resolucion, $persona));
                                    $correosEnviados++;
                                } catch (\Exception $e) {
                                    $erroresCorreo++;
                                    \Log::error('Error al enviar correo a ' . $persona->correo . ': ' . $e->getMessage());
                                }
                            } else {
                                $personasSinCorreo++;
                            }
                        }
                    }
                }
            }

            DB::commit();

            // Limpiar sesión
            session()->forget(['resolucion_paso1', 'resolucion_paso2']);

            // Construir mensaje de éxito
            $mensaje = '✅ Resolución creada exitosamente';
            
            if ($request->boolean('enviar_correo')) {
                $detalles = [];
                if ($correosEnviados > 0) {
                    $detalles[] = "{$correosEnviados} correo(s) enviado(s)";
                }
                if ($erroresCorreo > 0) {
                    $detalles[] = "{$erroresCorreo} error(es) al enviar";
                }
                if ($personasSinCorreo > 0) {
                    $detalles[] = "{$personasSinCorreo} persona(s) sin correo registrado";
                }
                
                if (count($detalles) > 0) {
                    $mensaje .= '. Notificaciones: ' . implode(', ', $detalles);
                }
            }

            return redirect()
                ->route('colaborador.resoluciones.show', $resolucion)
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Eliminar archivo temporal si existe
            if (isset($paso2['archivo_resolucion'])) {
                Storage::disk('public')->delete($paso2['archivo_resolucion']);
            }

            return redirect()
                ->back()
                ->with('error', '❌ Error al crear resolución: ' . $e->getMessage());
        }
    }

    public function revisarFirma(Request $request)
    {
        // Obtener IDs desde query string (GET) o desde body (POST)
        $idsJson = $request->input('resoluciones_ids');
        
        if (!$idsJson) {
            return redirect()->route('colaborador.resoluciones.index')->with('error', '❌ No se seleccionaron resoluciones');
        }
        
        $ids = json_decode($idsJson, true);
        
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('colaborador.resoluciones.index')->with('error', '❌ No se seleccionaron resoluciones');
        }

        $resoluciones = Resolucion::with(['estado', 'tipoResolucion', 'usuarioCreador.persona', 'personasInvolucradas'])
            ->whereIn('id_resolucion', $ids)
            ->whereNull('archivo_firmado') // Solo no firmadas
            ->get();

        if ($resoluciones->isEmpty()) {
            return redirect()->route('colaborador.resoluciones.index')->with('error', '❌ No hay resoluciones válidas para firmar');
        }

        return view('colaborador.resoluciones.revisar-firma', compact('resoluciones'));
    }

    public function firmarMasivo(Request $request)
    {
        $request->validate([
            'resoluciones_ids' => 'required|json',
            'enviar_whatsapp' => 'nullable|boolean',
            'enviar_correo' => 'nullable|boolean',
        ]);

        $ids = json_decode($request->resoluciones_ids);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', '❌ No se seleccionaron resoluciones');
        }

        DB::beginTransaction();
        try {
            $firmadas = 0;
            
            // Obtener el estado "Firmado" de la tabla estados_firma
            $estadoFirmado = \App\Models\EstadoFirma::where('nombre_estado', 'Firmado')->first();
            
            if (!$estadoFirmado) {
                throw new \Exception('No se encontró el estado "Firmado" en la tabla estados_firma');
            }
            
            foreach ($ids as $id) {
                $resolucion = Resolucion::find($id);
                
                if ($resolucion && !$resolucion->archivo_firmado) {
                    // Actualizar resolución
                    $resolucion->update([
                        'archivo_firmado' => 'firmado',  // Marcador temporal
                        'fecha_firma' => now(),
                        'id_usuario_firma' => Auth::id(),
                    ]);
                    
                    // Crear registro en cola_firma
                    \App\Models\ColaFirma::create([
                        'id_resolucion' => $resolucion->id_resolucion,
                        'id_usuario_solicita' => $resolucion->id_usuario,
                        'id_usuario_firmante' => Auth::id(),
                        'id_estado_firma' => $estadoFirmado->id_estado_firma,
                        'prioridad' => 'media',
                        'fecha_firma' => now(),
                        'observaciones' => 'Firmado mediante firma masiva',
                    ]);
                    
                    // Enviar notificaciones si están marcadas
                    if ($request->boolean('enviar_whatsapp') || $request->boolean('enviar_correo')) {
                        // Cargar personas involucradas
                        $resolucion->load('personasInvolucradas');
                        
                        foreach ($resolucion->personasInvolucradas as $persona) {
                            if ($request->boolean('enviar_correo') && $persona->correo) {
                                try {
                                    Mail::to($persona->correo)->send(new \App\Mail\ResolucionNotificacion($resolucion, $persona));
                                } catch (\Exception $e) {
                                    \Log::error('Error enviando correo: ' . $e->getMessage());
                                }
                            }
                            
                            // TODO: Implementar envío por WhatsApp
                            if ($request->boolean('enviar_whatsapp') && $persona->whatsapp) {
                                // Lógica de WhatsApp aquí
                            }
                        }
                    }
                    
                    $firmadas++;
                }
            }

            DB::commit();

            $mensaje = "✅ Se firmaron {$firmadas} resolución(es) correctamente.";
            
            if ($request->boolean('enviar_whatsapp') || $request->boolean('enviar_correo')) {
                $mensaje .= " Se enviaron las notificaciones correspondientes.";
            }

            return redirect()
                ->route('colaborador.resoluciones-firmadas.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '❌ Error al firmar resoluciones: ' . $e->getMessage());
        }
    }

}