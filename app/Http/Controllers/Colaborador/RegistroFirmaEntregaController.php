<?php
// filepath: app/Http/Controllers/Colaborador/RegistroFirmaEntregaController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\RegistroFirmaEntrega;
use App\Models\Resolucion;
use App\Models\PersonaResolucionDatos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RegistroFirmaEntregaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:resoluciones.ver', only: ['index', 'show']),
            new Middleware('permission:resoluciones.firmar', only: ['create', 'store', 'firmar']),
        ];
    }

    /**
     * Listar todos los registros de firma para entrega
     */
    public function index(Request $request)
    {
        $query = RegistroFirmaEntrega::with([
            'resolucion.tipoResolucion',
            'personaExterna',
            'usuarioFirmante.persona',
            'usuarioSolicita.persona'
        ])->orderBy('fecha_solicitud', 'desc');

        // Filtros
        if ($request->filled('estado')) {
            if ($request->estado === 'firmado') {
                $query->firmados();
            } elseif ($request->estado === 'pendiente') {
                $query->pendientes();
            } elseif ($request->estado === 'entregado') {
                $query->entregados();
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resolucion', function($q) use ($search) {
                $q->where('num_resolucion', 'like', "%{$search}%");
            })->orWhereHas('personaExterna', function($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$search}%")
                  ->orWhere('num_documento', 'like', "%{$search}%");
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_solicitud', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_solicitud', '<=', $request->fecha_hasta);
        }

        $registros = $query->paginate(20)->withQueryString();

        return view('colaborador.registro-firma-entrega.index', compact('registros'));
    }

    /**
     * Ver detalle de un registro
     */
    public function show(RegistroFirmaEntrega $registro)
    {
        $registro->load([
            'resolucion.tipoResolucion',
            'resolucion.usuarioCreador.persona',
            'personaExterna',
            'usuarioFirmante.persona',
            'usuarioSolicita.persona'
        ]);

        return view('colaborador.registro-firma-entrega.show', compact('registro'));
    }

    /**
     * Formulario para crear nueva solicitud de firma para entrega
     */
    public function create(Resolucion $resolucion)
    {
        // Obtener solo personas externas de esta resolución
        $personasExternas = $resolucion->personasRelacionadas()
            ->where('es_interna', false)
            ->get();

        if ($personasExternas->isEmpty()) {
            return redirect()
                ->route('colaborador.resoluciones.show', $resolucion)
                ->with('error', 'Esta resolución no tiene personas externas asociadas.');
        }

        return view('colaborador.registro-firma-entrega.create', compact('resolucion', 'personasExternas'));
    }

    /**
     * Crear nueva solicitud de firma para entrega
     */
    public function store(Request $request, Resolucion $resolucion)
    {
        $request->validate([
            'id_persona_resolucion_datos' => 'required|exists:persona_resolucion_datos,id_persona_resolucion_datos',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Verificar que la persona pertenece a la resolución y es externa
        $persona = PersonaResolucionDatos::where('id_persona_resolucion_datos', $request->id_persona_resolucion_datos)
            ->where('id_resolucion', $resolucion->id_resolucion)
            ->where('es_interna', false)
            ->firstOrFail();

        // Crear registro
        $registro = RegistroFirmaEntrega::create([
            'id_resolucion' => $resolucion->id_resolucion,
            'id_persona_resolucion_datos' => $persona->id_persona_resolucion_datos,
            'id_usuario_solicita' => Auth::id(),
            'id_usuario_firmante' => Auth::id(), // Por defecto el que solicita, puede cambiarse
            'firmado' => false,
            'fecha_solicitud' => now(),
            'observaciones' => $request->observaciones,
        ]);

        return redirect()
            ->route('colaborador.registro-firma-entrega.show', $registro)
            ->with('success', '✅ Solicitud de firma para entrega creada. Código: ' . $registro->codigo_verificacion);
    }

    /**
     * Marcar como firmado (simulación hasta integrar Firma Perú)
     */
    public function firmar(Request $request, RegistroFirmaEntrega $registro)
    {
        if ($registro->firmado) {
            return redirect()
                ->back()
                ->with('error', 'Este registro ya está firmado.');
        }

        $request->validate([
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Marcar como firmado
        $registro->marcarComoFirmado(
            Auth::id(),
            null, // Archivo firmado (pendiente integración Firma Perú)
            $request->observaciones
        );

        return redirect()
            ->route('colaborador.registro-firma-entrega.show', $registro)
            ->with('success', '✅ Registro marcado como firmado. Pendiente integración con Firma Perú.');
    }

    /**
     * Registrar entrega al cliente
     */
    public function registrarEntrega(Request $request, RegistroFirmaEntrega $registro)
    {
        if (!$registro->firmado) {
            return redirect()
                ->back()
                ->with('error', 'Debe firmar primero antes de registrar la entrega.');
        }

        if ($registro->fecha_entrega) {
            return redirect()
                ->back()
                ->with('error', 'Este registro ya tiene fecha de entrega.');
        }

        $request->validate([
            'correo_destino' => 'nullable|email',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $registro->registrarEntrega(
            $request->correo_destino,
            $request->observaciones
        );

        return redirect()
            ->route('colaborador.registro-firma-entrega.show', $registro)
            ->with('success', '✅ Entrega registrada exitosamente.');
    }

    /**
     * Ver registros de una resolución específica
     */
    public function porResolucion(Resolucion $resolucion)
    {
        $registros = $resolucion->registrosFirmaEntrega()
            ->with(['personaExterna', 'usuarioFirmante.persona'])
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        return view('colaborador.registro-firma-entrega.por-resolucion', compact('resolucion', 'registros'));
    }
}