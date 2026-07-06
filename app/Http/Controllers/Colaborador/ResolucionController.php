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
            new Middleware('permission:resoluciones.ver', only: ['index', 'show', 'descargar', 'descargarFirmado']),
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
        if (!Auth::user()->can('ver_todas_resoluciones') && $resolucion->id_usuario !== Auth::id()) {
            abort(403);
        }

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
            'entregas.personaEntrega.user',
            'entregas.usuarioFirma.persona',
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
        if (!Auth::user()->can('ver_todas_resoluciones') && $resolucion->id_usuario !== Auth::id()) {
            abort(403);
        }

        if (!$resolucion->archivo_resolucion || !Storage::disk('public')->exists($resolucion->archivo_resolucion)) {
            abort(404, 'Archivo no encontrado');
        }

        \App\Models\Auditoria::create([
            'tabla_afectada' => 'resolucion',
            'id_registro'    => $resolucion->id_resolucion,
            'accion'         => 'descargar',
            'id_usuario'     => Auth::id(),
            'ip_address'     => request()->ip(),
            'descripcion'    => "Descarga de resolución: {$resolucion->num_resolucion}",
        ]);

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
        if (!Auth::user()->can('ver_todas_resoluciones') && $resolucion->id_usuario !== Auth::id()) {
            abort(403);
        }

        if (!$resolucion->archivo_firmado || !Storage::disk('public')->exists($resolucion->archivo_firmado)) {
            abort(404, 'Archivo firmado no encontrado');
        }

        \App\Models\Auditoria::create([
            'tabla_afectada' => 'resolucion',
            'id_registro'    => $resolucion->id_resolucion,
            'accion'         => 'descargar_firmado',
            'id_usuario'     => Auth::id(),
            'ip_address'     => request()->ip(),
            'descripcion'    => "Descarga de resolución firmada: {$resolucion->num_resolucion}",
        ]);

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
     * Buscar persona externa por DNI: primero en BD, luego RENIEC.
     * Si no se encuentra en ninguno, no se permite agregar.
     */
    public function consultarReniec(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        // 1. Buscar en la BD primero
        $persona = Persona::where('num_documento', $request->dni)->first();

        if ($persona) {
            if ($persona->tipo_persona === 'colaborador') {
                return response()->json([
                    'success' => false,
                    'fuente'  => 'sistema',
                    'message' => 'Esta persona es colaborador interno de la DRE y no puede ser agregada como persona externa.',
                ], 422);
            }

            return response()->json([
                'success'          => true,
                'fuente'           => 'sistema',
                'id_persona'       => $persona->id_persona,
                'nombres'          => $persona->nombres,
                'apellido_paterno' => $persona->apellido_paterno,
                'apellido_materno' => $persona->apellido_materno,
                'obtenido_reniec'  => true,
                'message'          => 'Persona encontrada en el sistema.',
            ]);
        }

        // 2. No existe en BD: consultar RENIEC
        $resultado = (new ReniecService())->consultarDni($request->dni);

        if ($resultado && $resultado['success']) {
            return response()->json([
                'success'          => true,
                'fuente'           => 'reniec',
                'nombres'          => $resultado['nombres'],
                'apellido_paterno' => $resultado['apellido_paterno'],
                'apellido_materno' => $resultado['apellido_materno'] ?? '',
                'obtenido_reniec'  => true,
                'message'          => 'Datos obtenidos de RENIEC.',
            ]);
        }

        // 3. No encontrado en ningún lado
        return response()->json([
            'success' => false,
            'fuente'  => 'no_encontrado',
            'message' => 'DNI no encontrado en RENIEC. No es posible agregar a esta persona sin verificación.',
        ], 404);
    }

    /**
     * Verificar a quién se le entrega una resolución (Paso 2 de revisar-firma).
     * Distingue 3 casos: es colaborador interno (bloquear), ya es cliente (mostrar datos),
     * o no existe en el sistema (cuenta nueva).
     *
     * Si la persona viene de la lista de personas ya vinculadas a la resolución
     * (id_persona_resolucion_datos), se reutilizan los nombres/apellidos ya guardados
     * en lugar de volver a consultar RENIEC.
     */
    public function verificarReceptor(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
            'id_persona_resolucion_datos' => 'nullable|exists:persona_resolucion_datos,id_persona_resolucion_datos',
        ]);

        $persona = Persona::where('num_documento', $request->dni)->first();

        if ($persona && $persona->tipo_persona === 'colaborador') {
            return response()->json([
                'success' => false,
                'tipo' => 'colaborador',
                'message' => 'Esta persona trabaja en la DRE, no se le puede entregar por este medio.',
                'nombre_completo' => trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
            ], 422);
        }

        if ($persona && $persona->tipo_persona === 'cliente' && $persona->user) {
            return response()->json([
                'success' => true,
                'tipo' => 'cliente',
                'id_persona' => $persona->id_persona,
                'nombres' => $persona->nombres,
                'apellido_paterno' => $persona->apellido_paterno,
                'apellido_materno' => $persona->apellido_materno,
                'correo' => $persona->correo,
                'username' => $persona->user->username,
            ]);
        }

        // No existe en el sistema: ¿ya tenemos sus datos guardados en la resolución?
        if ($request->filled('id_persona_resolucion_datos')) {
            $pe = PersonaResolucionDatos::find($request->id_persona_resolucion_datos);

            if ($pe) {
                return response()->json([
                    'success' => true,
                    'tipo' => 'nuevo',
                    'nombres' => $pe->nombres,
                    'apellido_paterno' => $pe->apellido_paterno,
                    'apellido_materno' => $pe->apellido_materno,
                    'obtenido_reniec' => (bool) $pe->obtenido_reniec,
                ]);
            }
        }

        // Receptor no estaba en la lista: sí hace falta consultar RENIEC
        $resultado = (new ReniecService())->consultarDni($request->dni);

        return response()->json([
            'success' => true,
            'tipo' => 'nuevo',
            'nombres' => $resultado['nombres'] ?? '',
            'apellido_paterno' => $resultado['apellido_paterno'] ?? '',
            'apellido_materno' => $resultado['apellido_materno'] ?? '',
            'obtenido_reniec' => $resultado['success'] ?? false,
        ]);
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
            'personas_externas.*.obtenido_reniec' => 'required|in:true,1',
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
            'archivo_resolucion' => 'nullable|file|extensions:pdf,doc,docx|max:10240',
        ], [
            'archivo_resolucion.extensions' => 'El archivo debe ser de tipo: pdf, doc, docx.',
            'archivo_resolucion.max' => 'El archivo no debe pesar más de 10MB.',
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

            // Mover archivo si existe y está en temp
            if ($resolucion->archivo_resolucion && str_contains($resolucion->archivo_resolucion, 'resoluciones/temp/')) {
                $nombreArchivo = basename($resolucion->archivo_resolucion);
                $nuevoPath = 'resoluciones/' . $nombreArchivo;
                
                if (Storage::disk('public')->exists($resolucion->archivo_resolucion)) {
                    Storage::disk('public')->move($resolucion->archivo_resolucion, $nuevoPath);
                    $resolucion->update(['archivo_resolucion' => $nuevoPath]);
                }
            }

            // Guardar personas INTERNAS (trabajadores DRE con cuenta de usuario)
            if (isset($datosPaso1['personas_internas']) && is_array($datosPaso1['personas_internas'])) {
                foreach ($datosPaso1['personas_internas'] as $persona) {
                    // Obtener datos reales de la persona desde su usuario
                    $usuarioRelacionado = User::with('persona')->find($persona['id_user']);
                    
                    if ($usuarioRelacionado && $usuarioRelacionado->persona) {
                        $nombres = $usuarioRelacionado->persona->nombres;
                        $apellidoPaterno = $usuarioRelacionado->persona->apellido_paterno;
                        $apellidoMaterno = $usuarioRelacionado->persona->apellido_materno;
                    } else {
                        // Fallback por si acaso
                        $nombrePartes = explode(' ', $persona['nombre_completo']);
                        $nombres = $nombrePartes[0] ?? '';
                        $apellidoPaterno = $nombrePartes[1] ?? '';
                        $apellidoMaterno = isset($nombrePartes[2]) ? implode(' ', array_slice($nombrePartes, 2)) : '';
                    }

                    PersonaResolucionDatos::create([
                        'id_resolucion' => $resolucion->id_resolucion,
                        'id_user' => $persona['id_user'],
                        'tipo_documento' => 'DNI',
                        'num_documento' => $persona['num_documento'],
                        'nombres' => $nombres,
                        'apellido_paterno' => $apellidoPaterno,
                        'apellido_materno' => $apellidoMaterno,
                        'tipo_relacion' => $persona['tipo_relacion'],
                        'descripcion_relacion' => $persona['descripcion_relacion'] ?? null,
                        'obtenido_reniec' => false,
                        'es_interna' => true,
                    ]);
                }
            }

            // Guardar personas EXTERNAS (no tienen cuenta de usuario)
            if (isset($datosPaso1['personas_externas']) && is_array($datosPaso1['personas_externas'])) {
                foreach ($datosPaso1['personas_externas'] as $personaData) {
                    $personaRelacion = PersonaResolucionDatos::create([
                        'id_resolucion' => $resolucion->id_resolucion,
                        'id_user' => null,
                        'tipo_documento' => $personaData['tipo_documento'],
                        'num_documento' => $personaData['num_documento'] ?? null,
                        'nombres' => $personaData['nombres'],
                        'apellido_paterno' => $personaData['apellido_paterno'],
                        'apellido_materno' => $personaData['apellido_materno'] ?? null,
                        'tipo_relacion' => $personaData['tipo_relacion'],
                        'descripcion_relacion' => $personaData['descripcion_relacion'] ?? null,
                        'obtenido_reniec' => filter_var($personaData['obtenido_reniec'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'es_interna' => false,
                    ]);

                    // AUTO-CREAR Registro de Firma para Entrega (Cola de Firma para externos)
                    \App\Models\RegistroFirmaEntrega::create([
                        'id_resolucion' => $resolucion->id_resolucion,
                        'id_persona_resolucion_datos' => $personaRelacion->id_persona_resolucion_datos,
                        'id_usuario_solicita' => Auth::id(),
                        'id_usuario_firmante' => Auth::id(), // Por defecto el mismo
                        'firmado' => false,
                        'fecha_solicitud' => now(),
                    ]);
                }
            }

            // CREAR ENTRADA EN COLA DE FIRMA (Para que aparezca en el dashboard de firmas)
            $estadoPendienteFirma = \App\Models\EstadoFirma::where('nombre_estado', 'Pendiente')->first();
            \App\Models\ColaFirma::create([
                'id_resolucion' => $resolucion->id_resolucion,
                'id_usuario_solicita' => Auth::id(),
                'id_usuario_firmante' => Auth::id(), // El creador puede ser el primer firmante o asignarlo luego
                'id_estado_firma' => $estadoPendienteFirma?->id_estado_firma,
                'prioridad' => 'media',
                'fecha_solicitud' => now(),
                'fecha_limite' => now()->addDays(3),
                'observaciones' => 'Firma inicial generada automáticamente al crear resolución.',
            ]);

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
                    if ($user && $user->email) {
                        try {
                            Mail::to($user->email)->send(new ResolucionNotificacion($resolucion));
                        } catch (\Exception $e) {
                            \Log::error('Error enviando email de notificación: ' . $e->getMessage());
                        }
                    }
                }
                
                session()->flash('usuarios_notificados', count($request->usuarios_notificar));
            }

            return redirect()
                ->route('colaborador.resoluciones.show', $resolucion)
                ->with('success', '✅ Resolución creada exitosamente' . 
                    (session('usuarios_notificados') ? ' y se notificó a ' . session('usuarios_notificados') . ' usuario(s)' : ''));

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

        // Una resolución puede entregarse varias veces (a distintas personas, o de nuevo
        // a la misma), así que ya estar firmada no la excluye de este flujo.
        $resoluciones = Resolucion::with(['estado', 'tipoResolucion', 'usuarioCreador.persona', 'personasInvolucradas'])
            ->whereIn('id_resolucion', $ids)
            ->where('id_usuario', Auth::id())
            ->get();

        if ($resoluciones->isEmpty()) {
            return redirect()->route('colaborador.resoluciones.index')->with('error', '❌ No se encontraron las resoluciones seleccionadas');
        }

        // Personas externas ya vinculadas a estas resoluciones desde su creación
        // (pueden tener el DNI sin completar todavía)
        $personasExternas = PersonaResolucionDatos::whereIn('id_resolucion', $ids)
            ->where('es_interna', false)
            ->get();

        return view('colaborador.resoluciones.revisar-firma', compact('resoluciones', 'personasExternas'));
    }

    /**
     * Completar el DNI de una persona externa que se vinculó a una resolución
     * sin DNI al momento de crearla (AJAX, desde Paso 2 de revisar-firma).
     */
    public function actualizarDniPersonaRelacionada(Request $request, PersonaResolucionDatos $personaResolucionDatos)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        $personaResolucionDatos->update(['num_documento' => $request->dni]);

        // Primera vez que se vincula este DNI a esta identidad: corroborar con RENIEC
        $advertencia = null;
        $resultado = (new ReniecService())->consultarDni($request->dni);

        if ($resultado && ($resultado['success'] ?? false)) {
            $nombreReniec = trim(($resultado['nombres'] ?? '') . ' ' . ($resultado['apellido_paterno'] ?? ''));
            $nombreGuardado = trim($personaResolucionDatos->nombres . ' ' . $personaResolucionDatos->apellido_paterno);

            if (strcasecmp($nombreReniec, $nombreGuardado) !== 0) {
                $advertencia = "⚠️ RENIEC registra a \"{$nombreReniec}\" para este DNI, pero aquí se guardó \"{$nombreGuardado}\". Verifica antes de continuar.";
            }
        }

        return response()->json([
            'success' => true,
            'dni' => $request->dni,
            'advertencia' => $advertencia,
        ]);
    }

    public function firmarMasivo(Request $request)
    {
        $request->validate([
            'resoluciones_ids' => 'required|json',
            'archivo_firmado' => 'required|file|mimes:pdf|max:15360',
            'dni' => 'required|digits:8',
            'tipo_receptor' => 'required|in:cliente,nuevo',
            'nombres' => 'required_if:tipo_receptor,nuevo|nullable|string',
            'apellido_paterno' => 'required_if:tipo_receptor,nuevo|nullable|string',
            'apellido_materno' => 'nullable|string',
            'email_destino' => 'required_if:tipo_receptor,nuevo|nullable|email',
            'enviar_correo' => 'nullable|boolean',
        ]);

        $ids = json_decode($request->resoluciones_ids);

        if (empty($ids)) {
            return redirect()->back()->with('error', '❌ No se seleccionaron resoluciones');
        }

        // Defensa adicional: nunca permitir entregar a un colaborador interno
        $personaExistente = Persona::where('num_documento', $request->dni)->first();
        if ($personaExistente && $personaExistente->tipo_persona === 'colaborador') {
            return redirect()->back()->with('error', '❌ Esta persona trabaja en la DRE, no se le puede entregar por este medio.');
        }

        DB::beginTransaction();
        try {
            $archivo = $request->file('archivo_firmado');
            $nombreArchivo = 'firmado_' . time() . '_' . $archivo->getClientOriginalName();
            $pathFirmado = $archivo->storeAs('resoluciones/firmadas', $nombreArchivo, 'public');

            $estadoFirmado = \App\Models\EstadoFirma::where('nombre_estado', 'Firmado')->first();
            if (!$estadoFirmado) {
                throw new \Exception('No se encontró el estado "Firmado" en la tabla estados_firma');
            }

            // 1. Resolver al receptor: cliente existente o cuenta nueva
            $credencialesNuevas = null;

            if ($request->tipo_receptor === 'cliente') {
                $persona = Persona::where('num_documento', $request->dni)->where('tipo_persona', 'cliente')->firstOrFail();

                if ($request->filled('email_destino') && $request->email_destino !== $persona->correo) {
                    $persona->update(['correo' => $request->email_destino]);
                }
            } else {
                // Puede existir un registro de Persona "cliente" sin cuenta de usuario todavía
                // (verificarReceptor() solo devuelve 'cliente' cuando ya tiene User). Reusarlo
                // evita chocar con el único (tipo_documento, num_documento) de la tabla persona.
                $persona = Persona::where('tipo_documento', 'DNI')->where('num_documento', $request->dni)->first();

                if ($persona) {
                    $persona->update([
                        'nombres' => $request->nombres,
                        'apellido_paterno' => $request->apellido_paterno,
                        'apellido_materno' => $request->apellido_materno,
                        'correo' => $request->email_destino,
                        'datos_completos' => true,
                    ]);
                } else {
                    $persona = Persona::create([
                        'tipo_persona' => 'cliente',
                        'tipo_documento' => 'DNI',
                        'num_documento' => $request->dni,
                        'nombres' => $request->nombres,
                        'apellido_paterno' => $request->apellido_paterno,
                        'apellido_materno' => $request->apellido_materno,
                        'correo' => $request->email_destino,
                        'datos_completos' => true,
                        'i_active' => true,
                    ]);
                }

                // Usuario de acceso = primera letra del nombre + apellido paterno (ej. "jperez").
                // La contraseña es su DNI. El correo del sistema solo existe porque la columna
                // lo requiere (única, no nula); no se usa para iniciar sesión.
                $primerNombre = explode(' ', trim($request->nombres))[0];
                $usernameBase = strtolower(substr($primerNombre, 0, 1) . $request->apellido_paterno);
                $username = $usernameBase;
                $i = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $usernameBase . $i++;
                }

                $correoSistema = $username . '@dre.com';
                $contador = 1;
                while (User::where('email', $correoSistema)->exists()) {
                    $correoSistema = $username . $contador++ . '@dre.com';
                }

                $passwordPlano = $request->dni;

                $user = User::create([
                    'id_persona' => $persona->id_persona,
                    'name' => trim($request->nombres . ' ' . $request->apellido_paterno . ' ' . $request->apellido_materno),
                    'username' => $username,
                    'email' => $correoSistema,
                    'password' => Hash::make($passwordPlano),
                    'tipo_acceso' => 'cliente',
                    'i_active' => true,
                ]);

                \App\Models\Cliente::firstOrCreate(
                    ['id_persona' => $persona->id_persona],
                    ['i_active' => true]
                );

                $credencialesNuevas = [
                    'username' => $username,
                    'password' => $passwordPlano,
                ];
            }

            // 2. Aplicar la firma a cada resolución seleccionada
            $firmadas = 0;
            $resolucionesFirmadas = [];

            foreach ($ids as $id) {
                $resolucion = Resolucion::find($id);

                if ($resolucion) {
                    // Campos en la resolución = "última entrega" (acceso rápido).
                    // El historial completo de entregas vive en entrega_resolucion,
                    // porque una misma resolución puede entregarse varias veces.
                    $resolucion->update([
                        'archivo_firmado' => $pathFirmado,
                        'fecha_firma' => now(),
                        'id_usuario_firma' => Auth::id(),
                        'id_persona_entrega' => $persona->id_persona,
                        'correo_entrega' => $persona->correo,
                    ]);

                    \App\Models\EntregaResolucion::create([
                        'id_resolucion' => $resolucion->id_resolucion,
                        'id_persona_entrega' => $persona->id_persona,
                        'correo_entrega' => $persona->correo,
                        'archivo_firmado' => $pathFirmado,
                        'id_usuario_firma' => Auth::id(),
                        'cuenta_creada' => (bool) $credencialesNuevas,
                        'fecha_entrega' => now(),
                    ]);

                    \App\Models\ColaFirma::create([
                        'id_resolucion' => $resolucion->id_resolucion,
                        'id_usuario_solicita' => $resolucion->id_usuario ?? Auth::id(),
                        'id_usuario_firmante' => Auth::id(),
                        'id_estado_firma' => $estadoFirmado->id_estado_firma,
                        'prioridad' => 'media',
                        'fecha_firma' => now(),
                        'observaciones' => 'Firmado y entregado a ' . trim($request->nombres ?? $persona->nombres . ' ' . ($request->apellido_paterno ?? $persona->apellido_paterno)),
                    ]);

                    $resolucionesFirmadas[] = $resolucion;
                    $firmadas++;
                }
            }

            // 3. Notificaciones: credenciales nuevas (obligatorio) + aviso de cada resolución (opcional)
            if ($credencialesNuevas) {
                try {
                    Mail::to($persona->correo)->send(new \App\Mail\CredencialesAcceso([
                        'nombre' => trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
                        'username' => $credencialesNuevas['username'],
                        'password' => $credencialesNuevas['password'],
                        'resolucion' => $resolucionesFirmadas[0] ?? null,
                    ]));
                } catch (\Exception $e) {
                    \Log::error('Error enviando credenciales de acceso: ' . $e->getMessage());
                }
            }

            if ($request->boolean('enviar_correo') && $persona->correo) {
                foreach ($resolucionesFirmadas as $resolucion) {
                    try {
                        Mail::to($persona->correo)->send(new \App\Mail\ResolucionNotificacion($resolucion, $persona));
                    } catch (\Exception $e) {
                        \Log::error('Error enviando correo: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            $mensaje = "✅ Se firmaron {$firmadas} resolución(es) y se entregaron a "
                . trim($persona->nombres . ' ' . $persona->apellido_paterno) . '.';

            if ($credencialesNuevas) {
                $mensaje .= ' Se creó su cuenta y se le enviaron las credenciales de acceso.';
            }

            return redirect()
                ->route('colaborador.resoluciones-firmadas.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($pathFirmado)) {
                Storage::disk('public')->delete($pathFirmado);
            }
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
                'success' => true,
                'usuario' => [
                    'id' => $user->id,
                    'nombre_completo' => trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
                    'dni' => $persona->num_documento,
                    'email' => $user->email,
                    'iniciales' => $iniciales
                ]
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
                    'success' => false,
                    'message' => 'No se encontraron usuarios con ese nombre'
                ], 404);
            }

            // Si hay varios resultados, devolver el primero
            $persona = $personas->first();
            $user = $persona->user;
            
            $iniciales = strtoupper(
                substr($persona->nombres ?? '', 0, 1) . 
                substr($persona->apellido_paterno ?? '', 0, 1)
            );

            return response()->json([
                'success' => true,
                'usuario' => [
                    'id' => $user->id,
                    'nombre_completo' => trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
                    'dni' => $persona->num_documento,
                    'email' => $user->email,
                    'iniciales' => $iniciales
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Debe proporcionar dni o nombre'
        ], 400);
    }

}