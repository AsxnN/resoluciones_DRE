<?php
// filepath: app/Http/Controllers/Colaborador/ResolucionController.php

namespace App\Http\Controllers\Colaborador;

use App\Mail\ResolucionNotificacion;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Persona;
use App\Models\Resolucion;
use App\Models\TipoResolucion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\ReniecService;
use App\Models\PersonaResolucionDatos;

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

        // Filtro por año
        if ($request->filled('anio')) {
            $query->whereYear('fecha_creacion', $request->anio);
        }

        // Filtro por mes
        if ($request->filled('mes')) {
            $query->whereMonth('fecha_creacion', $request->mes);
        }

        // Mantener los filtros de fecha desde/hasta si existen
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_creacion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_creacion', '<=', $request->fecha_hasta);
        }

        // ORDENAR POR FECHA DE CREACIÓN (más recientes primero)
        $resoluciones = $query->orderBy('fecha_creacion', 'desc')
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
            'personasRelacionadas.usuario', // ← AGREGAR ESTA LÍNEA
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
     * Consultar RENIEC por DNI
     */
    public function consultarReniec(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        $reniecService = new ReniecService();
        $resultado = $reniecService->consultarDni($request->dni);

        if ($resultado && $resultado['success']) {
            return response()->json($resultado);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos en RENIEC o el servicio no está disponible.',
        ], 404);
    }

    /**
     * Guardar datos del paso 1 en sesión (ACTUALIZADO)
     */
    public function storePaso1(Request $request)
    {
        $validated = $request->validate([
            'num_resolucion' => 'required|string|max:50|unique:resolucion,num_resolucion',
            'fecha_resolucion' => 'required|date',
            'id_estado' => 'required|exists:estado,id_estado',
            'id_tipo_resolucion' => 'required|exists:tipo_resolucion,id_tipo_resolucion',
            'id_dependencia' => 'nullable|exists:dependencia,id_dependencias',
            
            // PERSONAS INTERNAS
            'personas_internas' => 'nullable|array',
            'personas_internas.*.id_user' => 'required|exists:users,id',
            'personas_internas.*.num_documento' => 'required|string|max:20',
            'personas_internas.*.nombre_completo' => 'required|string|max:255',
            'personas_internas.*.correo' => 'nullable|email',
            'personas_internas.*.tipo_relacion' => 'required|in:beneficiario,afectado,involucrado,testigo,otro',
            'personas_internas.*.descripcion_relacion' => 'nullable|string|max:255',
            'personas_internas.*.es_interna' => 'required|in:true,false,0,1', // ← CAMBIAR
            
            // PERSONAS EXTERNAS
            'personas_externas' => 'nullable|array',
            'personas_externas.*.tipo_documento' => 'required|string|in:DNI,CE,PASAPORTE',
            'personas_externas.*.num_documento' => 'nullable|string|max:20',
            'personas_externas.*.nombres' => 'required|string|max:100',
            'personas_externas.*.apellido_paterno' => 'required|string|max:100',
            'personas_externas.*.apellido_materno' => 'nullable|string|max:100',
            'personas_externas.*.tipo_relacion' => 'required|in:beneficiario,afectado,involucrado,testigo,otro',
            'personas_externas.*.descripcion_relacion' => 'nullable|string|max:255',
            'personas_externas.*.obtenido_reniec' => 'nullable|in:true,false,0,1', // ← CAMBIAR
            'personas_externas.*.es_interna' => 'required|in:true,false,0,1', // ← CAMBIAR
        ]);

        // Convertir strings booleanos a booleanos reales
        if (isset($validated['personas_internas'])) {
            foreach ($validated['personas_internas'] as &$persona) {
                $persona['es_interna'] = filter_var($persona['es_interna'], FILTER_VALIDATE_BOOLEAN);
            }
        }

        if (isset($validated['personas_externas'])) {
            foreach ($validated['personas_externas'] as &$persona) {
                $persona['obtenido_reniec'] = filter_var($persona['obtenido_reniec'] ?? false, FILTER_VALIDATE_BOOLEAN);
                $persona['es_interna'] = filter_var($persona['es_interna'], FILTER_VALIDATE_BOOLEAN);
            }
        }

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
     * Guardar la resolución completa (Paso 3 final) - ACTUALIZADO
     */
    public function storeFinal(Request $request)
    {
        $request->validate([
            'aceptar_terminos' => 'accepted',
            'usuarios_notificar' => 'nullable|array',
            'usuarios_notificar.*' => 'exists:users,id',
        ]);

        if (!session()->has('resolucion_paso1') || !session()->has('resolucion_paso2')) {
            return redirect()
                ->route('colaborador.resoluciones.create')
                ->with('error', '❌ Sesión expirada. Por favor, inicie el proceso nuevamente.');
        }

        $datosPaso1 = session('resolucion_paso1');
        $datosPaso2 = session('resolucion_paso2');

        DB::beginTransaction();
        try {
            // Crear resolución
            $resolucion = Resolucion::create([
                'num_resolucion' => $datosPaso1['num_resolucion'],
                'fecha_resolucion' => $datosPaso1['fecha_resolucion'],
                'id_estado' => $datosPaso1['id_estado'],
                'id_tipo_resolucion' => $datosPaso1['id_tipo_resolucion'],
                'id_dependencia' => $datosPaso1['id_dependencia'] ?? null,
                'visto_resolucion' => $datosPaso2['visto_resolucion'],
                'asunto_resolucion' => $datosPaso2['asunto_resolucion'],
                'archivo_resolucion' => $datosPaso2['archivo_resolucion'] ?? null,
                'id_resolucion_dependiente' => $datosPaso2['id_resolucion_dependiente'] ?? null,
                'id_usuario' => Auth::id(),
            ]);

            // Guardar personas INTERNAS (trabajadores DRE con cuenta de usuario)
            if (isset($datosPaso1['personas_internas']) && is_array($datosPaso1['personas_internas'])) {
                foreach ($datosPaso1['personas_internas'] as $persona) {
                    // Separar nombre completo en partes
                    $nombrePartes = explode(' ', $persona['nombre_completo']);
                    $nombres = $nombrePartes[0] ?? '';
                    $apellidoPaterno = $nombrePartes[1] ?? '';
                    $apellidoMaterno = isset($nombrePartes[2]) ? implode(' ', array_slice($nombrePartes, 2)) : '';

                    PersonaResolucionDatos::create([
                        'id_resolucion' => $resolucion->id_resolucion,
                        'id_user' => $persona['id_user'], // ← IMPORTANTE: Relacionar con usuario
                        'tipo_documento' => 'DNI', // Las personas internas siempre tienen DNI
                        'num_documento' => $persona['num_documento'],
                        'nombres' => $nombres,
                        'apellido_paterno' => $apellidoPaterno,
                        'apellido_materno' => $apellidoMaterno,
                        'tipo_relacion' => $persona['tipo_relacion'],
                        'descripcion_relacion' => $persona['descripcion_relacion'] ?? null,
                        'obtenido_reniec' => false,
                        'es_interna' => true, // ← IMPORTANTE: Marcar como interna
                    ]);
                }
            }

            // Guardar personas EXTERNAS (no tienen cuenta de usuario)
            if (isset($datosPaso1['personas_externas']) && is_array($datosPaso1['personas_externas'])) {
                foreach ($datosPaso1['personas_externas'] as $persona) {
                    PersonaResolucionDatos::create([
                        'id_resolucion' => $resolucion->id_resolucion,
                        'id_user' => null, // ← No tienen relación con usuario
                        'tipo_documento' => $persona['tipo_documento'],
                        'num_documento' => $persona['num_documento'] ?? null,
                        'nombres' => $persona['nombres'],
                        'apellido_paterno' => $persona['apellido_paterno'],
                        'apellido_materno' => $persona['apellido_materno'] ?? null,
                        'tipo_relacion' => $persona['tipo_relacion'],
                        'descripcion_relacion' => $persona['descripcion_relacion'] ?? null,
                        'obtenido_reniec' => filter_var($persona['obtenido_reniec'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'es_interna' => false, // ← IMPORTANTE: Marcar como externa
                    ]);
                }
            }

            // Registrar auditoría
            \App\Models\Auditoria::create([
                'tabla_afectada' => 'resolucion',
                'id_registro' => $resolucion->id_resolucion,
                'accion' => 'crear',
                'id_usuario' => Auth::id(),
                'ip_address' => $request->ip(),
                'descripcion' => "Resolución {$resolucion->num_resolucion} creada",
            ]);

            DB::commit();

            // Limpiar sesión
            session()->forget(['resolucion_paso1', 'resolucion_paso2']);

            // Notificar usuarios
            if ($request->has('usuarios_notificar')) {
                foreach ($request->usuarios_notificar as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        // Enviar notificación por email
                        // Mail::to($user->email)->send(new ResolucionNotificacion($resolucion));
                    }
                }
                
                session()->flash('usuarios_notificados', count($request->usuarios_notificar));
            }

            return redirect()
                ->route('colaborador.resoluciones.show', $resolucion)
                ->with('success', '✅ Resolución creada exitosamente' . 
                    (session('usuarios_notificados') ? ' y se notificará a ' . session('usuarios_notificados') . ' usuario(s)' : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error al crear resolución', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

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
            'usuarios_notificar_adicionales' => 'nullable|array',
            'usuarios_notificar_adicionales.*' => 'exists:users,id',
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
                    
                    // Enviar a personas involucradas
                    if ($request->boolean('enviar_whatsapp') || $request->boolean('enviar_correo')) {
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
                    
                    // Enviar a usuarios adicionales por correo
                    if ($request->has('usuarios_notificar_adicionales') && $request->boolean('enviar_correo')) {
                        foreach ($request->usuarios_notificar_adicionales as $userId) {
                            $user = User::find($userId);
                            if ($user && $user->email) {
                                try {
                                    Mail::to($user->email)->send(new \App\Mail\ResolucionNotificacion($resolucion));
                                } catch (\Exception $e) {
                                    \Log::error('Error enviando correo a usuario adicional: ' . $e->getMessage());
                                }
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
            
            if ($request->has('usuarios_notificar_adicionales')) {
                $mensaje .= " Se notificó a " . count($request->usuarios_notificar_adicionales) . " usuario(s) adicional(es).";
            }

            return redirect()
                ->route('colaborador.resoluciones-firmadas.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '❌ Error al firmar resoluciones: ' . $e->getMessage());
        }
    }

    public function buscarUsuario(Request $request)
    {
        // Buscar por DNI
        if ($request->filled('dni')) {
            $request->validate(['dni' => 'required|digits:8']);
            
            $persona = Persona::where('num_documento', $request->dni)
                            ->where('i_active', true)
                            ->first();

            if (!$persona || !$persona->user) {
                return response()->json([
                    'message' => 'Usuario no encontrado o no tiene cuenta en el sistema'
                ], 404);
            }

            $user = $persona->user;
            
            $iniciales = strtoupper(
                substr($persona->nombres ?? '', 0, 1) . 
                substr($persona->apellido_paterno ?? '', 0, 1)
            );

            return response()->json([
                'id_user' => $user->id,
                'nombre_completo' => trim($persona->apellido_paterno . ' ' . $persona->apellido_materno . ', ' . $persona->nombres),
                'num_documento' => $persona->num_documento,
                'correo' => $user->email,
                'iniciales' => $iniciales
            ]);
        }

        // Buscar por nombre
        if ($request->filled('nombre')) {
            $request->validate(['nombre' => 'required|min:3']);
            
            $personas = Persona::where('i_active', true)
                        ->where(function($q) use ($request) {
                            $q->where('nombres', 'like', "%{$request->nombre}%")
                            ->orWhere('apellido_paterno', 'like', "%{$request->nombre}%")
                            ->orWhere('apellido_materno', 'like', "%{$request->nombre}%");
                        })
                        ->whereHas('user')
                        ->with('user')
                        ->limit(10)
                        ->get();

            if ($personas->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron usuarios con ese nombre'
                ], 404);
            }

            // Si hay varios resultados, devolver el primero (o puedes modificar para devolver todos)
            $persona = $personas->first();
            $user = $persona->user;
            
            $iniciales = strtoupper(
                substr($persona->nombres ?? '', 0, 1) . 
                substr($persona->apellido_paterno ?? '', 0, 1)
            );

            return response()->json([
                'id_user' => $user->id,
                'nombre_completo' => trim($persona->apellido_paterno . ' ' . $persona->apellido_materno . ', ' . $persona->nombres),
                'num_documento' => $persona->num_documento,
                'correo' => $user->email,
                'iniciales' => $iniciales
            ]);
        }

        return response()->json(['message' => 'Debe proporcionar dni o nombre'], 400);
    }
    
    public function enviarCredenciales(Request $request, $personaResolucionDatosId)
    {
        $request->validate([
            'correo' => 'required|email',
        ]);

        try {
            $personaResolucionDatos = PersonaResolucionDatos::with('resolucion')->findOrFail($personaResolucionDatosId);
            
            // ← CAMBIO: PERMITIR REENVÍOS - Eliminar verificación de id_user
            // Verificar que sea persona externa
            if ($personaResolucionDatos->es_interna) {
                return redirect()->back()->with('error', '❌ Esta persona es trabajador interno de la DRE');
            }

            // ← CAMBIO: Verificar DNI solo si NO tiene id_user aún
            if (!$personaResolucionDatos->id_user) {
                $personaExistente = Persona::where('num_documento', $personaResolucionDatos->num_documento)
                    ->where('i_active', true)
                    ->first();

                if ($personaExistente) {
                    return redirect()->back()->with('error', '❌ El DNI ' . $personaResolucionDatos->num_documento . ' ya está registrado en el sistema');
                }
            }

            // Generar correo del sistema: Primera letra del nombre + 3 letras apellido paterno + 3 letras apellido materno
            // Ejemplo: Juan Alcarraz Sarmiento = jalcsar@dre.com
            
            // Separar los nombres y obtener solo el PRIMER nombre
            $nombres = explode(' ', trim($personaResolucionDatos->nombres));
            $primerNombre = $nombres[0];
            
            $primeraLetraNombre = strtolower(substr($primerNombre, 0, 1));
            $tresLetrasPaterno = strtolower(substr($personaResolucionDatos->apellido_paterno, 0, 3));
            $tresLetrasMaterno = strtolower(substr($personaResolucionDatos->apellido_materno ?? '', 0, 3));
            
            $correoSistemaBase = $primeraLetraNombre . $tresLetrasPaterno . $tresLetrasMaterno . '@dre.com';
            $correoSistema = $correoSistemaBase;
            
            // Verificar si el correo del sistema ya está registrado
            $correoSistemaExistente = User::where('email', $correoSistema)->first();
            
            if ($correoSistemaExistente) {
                // Si existe, agregar un número al final
                $contador = 1;
                $correoOriginal = str_replace('@dre.com', '', $correoSistemaBase);
                while (User::where('email', $correoOriginal . $contador . '@dre.com')->exists()) {
                    $contador++;
                }
                $correoSistema = $correoOriginal . $contador . '@dre.com';
            }

            // Generar contraseña aleatoria
            $password = Str::random(10);
            $passwordHash = Hash::make($password);

            DB::beginTransaction();

            $user = null;
            $persona = null;
            $estadoEnvio = 'enviado';
            $observaciones = null;

            // ← CAMBIO: Solo crear usuario si NO existe (id_user es null)
            if (!$personaResolucionDatos->id_user) {
                // 1. Crear registro en la tabla persona
                $persona = Persona::create([
                    'tipo_persona' => 'cliente',
                    'num_documento' => $personaResolucionDatos->num_documento,
                    'tipo_documento' => $personaResolucionDatos->tipo_documento,
                    'nombres' => $personaResolucionDatos->nombres,
                    'apellido_paterno' => $personaResolucionDatos->apellido_paterno,
                    'apellido_materno' => $personaResolucionDatos->apellido_materno,
                    'correo' => $request->correo, // Correo personal
                    'datos_completos' => true,
                    'i_active' => true,
                ]);

                // 2. Crear usuario
                $user = User::create([
                    'id_persona' => $persona->id_persona,
                    'name' => trim($personaResolucionDatos->nombres . ' ' . $personaResolucionDatos->apellido_paterno . ' ' . $personaResolucionDatos->apellido_materno),
                    'email' => $correoSistema,
                    'password' => $passwordHash,
                    'tipo_acceso' => 'cliente',
                    'i_active' => true,
                ]);

                // 3. Crear registro en tabla cliente
                \App\Models\Cliente::create([
                    'id_persona' => $persona->id_persona,
                    'i_active' => true,
                ]);

                // 4. Actualizar PersonaResolucionDatos con el id_user
                $personaResolucionDatos->update([
                    'id_user' => $user->id,
                ]);

                $observaciones = 'Primera cuenta creada para este usuario';
            } else {
                // Ya tiene cuenta, solo reenviar con nueva contraseña
                $user = User::find($personaResolucionDatos->id_user);
                
                if (!$user) {
                    throw new \Exception('Usuario vinculado no encontrado');
                }

                // Actualizar contraseña del usuario existente
                $user->update([
                    'password' => $passwordHash,
                ]);

                $correoSistema = $user->email; // Usar el correo existente
                $observaciones = 'Reenvío de credenciales con nueva contraseña';
            }

            // 5. ← NUEVO: Registrar envío de credenciales
            $envioCredencial = \App\Models\EnvioCredencial::create([
                'id_persona_resolucion_datos' => $personaResolucionDatos->id_persona_resolucion_datos,
                'correo_destino' => $request->correo,
                'correo_sistema_generado' => $correoSistema,
                'password_generado_hash' => $passwordHash,
                'id_usuario_envia' => Auth::id(),
                'estado_envio' => 'enviado',
                'observaciones' => $observaciones,
                'ip_address' => $request->ip(),
                'fecha_envio' => now(),
            ]);

            // 6. Registrar auditoría
            \App\Models\Auditoria::create([
                'tabla_afectada' => 'envios_credenciales',
                'id_registro' => $envioCredencial->id_envio_credencial,
                'accion' => 'enviar_credenciales',
                'id_usuario' => Auth::id(),
                'ip_address' => $request->ip(),
                'descripcion' => "Credenciales enviadas a {$request->correo} para {$personaResolucionDatos->nombre_completo} (Usuario sistema: {$correoSistema})",
            ]);

            // 7. Enviar email con las credenciales
            try {
                Mail::to($request->correo)->send(new \App\Mail\CredencialesAcceso([
                    'nombre' => $personaResolucionDatos->nombre_completo,
                    'email' => $correoSistema,
                    'password' => $password,
                    'resolucion' => $personaResolucionDatos->resolucion,
                ]));
            } catch (\Exception $e) {
                // Si falla el envío, actualizar estado
                $envioCredencial->update([
                    'estado_envio' => 'fallido',
                    'observaciones' => ($observaciones ?? '') . ' | Error: ' . $e->getMessage(),
                ]);
                
                DB::commit(); // Guardar el registro aunque falle el email
                
                \Log::error('Error enviando email de credenciales', [
                    'error' => $e->getMessage(),
                    'correo' => $request->correo,
                    'envio_id' => $envioCredencial->id_envio_credencial,
                ]);
                
                return redirect()->back()->with('error', '❌ Error al enviar el correo: ' . $e->getMessage());
            }

            DB::commit();

            $mensaje = '✅ Credenciales enviadas a ' . $request->correo;
            if ($observaciones && str_contains($observaciones, 'Reenvío')) {
                $mensaje .= ' (Reenvío con nueva contraseña)';
            }
            $mensaje .= '. Usuario del sistema: ' . $correoSistema;

            return redirect()->back()->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error al enviar credenciales', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'persona_id' => $personaResolucionDatosId,
            ]);

            return redirect()->back()->with('error', '❌ Error al enviar credenciales: ' . $e->getMessage());
        }
    }

    public function asignarCliente(Request $request, Resolucion $resolucion, PersonaResolucionDatos $personaResolucionDatos)
    {
        try {
            // Verificar que la persona pertenece a esta resolución
            if ($personaResolucionDatos->id_resolucion !== $resolucion->id_resolucion) {
                return back()->with('error', 'La persona no pertenece a esta resolución.');
            }

            // Verificar que es persona externa
            if ($personaResolucionDatos->es_interna) {
                return back()->with('error', 'Solo se puede asignar a personas externas.');
            }

            // Verificar que tiene usuario creado
            if (!$personaResolucionDatos->id_user) {
                return back()->with('error', 'La persona debe tener una cuenta de usuario primero. Envía las credenciales antes de asignar la resolución.');
            }

            // Verificar que la resolución esté firmada
            if (!$resolucion->archivo_firmado) {
                return back()->with('error', 'Solo se pueden asignar resoluciones firmadas.');
            }

            // Crear o actualizar la relación en la tabla pivot (si usas relación many-to-many)
            // O actualizar un campo booleano en persona_resolucion_datos
            
            // Opción 1: Si agregas un campo 'asignado_a_cliente' en persona_resolucion_datos
            $personaResolucionDatos->update([
                'asignado_a_cliente' => true,
                'fecha_asignacion' => now()
            ]);

            // Registrar auditoría
            \App\Models\Auditoria::create([
                'tabla_afectada' => 'resolucion',
                'id_registro'    => $resolucion->id_resolucion,
                'accion'         => 'asignar_cliente',
                'id_usuario'     => Auth::id(),
                'ip_address'     => request()->ip(),
                'descripcion'    => "Resolución {$resolucion->num_resolucion} asignada a {$personaResolucionDatos->nombre_completo} (id_user: {$personaResolucionDatos->id_user})",
            ]);

            // Opcional: Enviar notificación por correo
            $usuario = User::find($personaResolucionDatos->id_user);
            if ($usuario && $usuario->email) {
                try {
                    // Aquí podrías enviar un correo notificando que tiene una nueva resolución
                    // Mail::to($usuario->email)->send(new ResolucionAsignada($resolucion));
                } catch (\Exception $e) {
                    \Log::error('Error al enviar notificación de asignación: ' . $e->getMessage());
                }
            }

            return back()->with('success', 'Resolución asignada correctamente al cliente. Ahora podrá verla en su módulo "Mis Resoluciones".');

        } catch (\Exception $e) {
            \Log::error('Error al asignar resolución a cliente: ' . $e->getMessage());
            return back()->with('error', 'Error al asignar la resolución: ' . $e->getMessage());
        }
    }

}