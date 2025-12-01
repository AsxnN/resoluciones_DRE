<?php
// filepath: app/Http/Controllers/Colaborador/ResolucionFirmadaController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\ColaFirma;
use App\Models\EstadoFirma;
use App\Models\HistorialFirma;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ResolucionFirmadaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:resoluciones.firmar'),
        ];
    }

    /**
     * Listar resoluciones firmadas
     */
    public function index(Request $request)
    {
        $query = Resolucion::with(['estado', 'tipoResolucion', 'usuarioFirmante.persona'])
            ->whereNotNull('archivo_firmado');

        // Si no tiene permiso de ver todas, solo ver las que firmó
        if (!Auth::user()->can('ver_todas_resoluciones')) {
            $query->where('id_usuario_firma', Auth::id());
        }

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('num_resolucion', 'like', "%{$search}%")
                  ->orWhere('asunto_resolucion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_firma', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_firma', '<=', $request->fecha_hasta);
        }

        $resoluciones = $query->orderBy('fecha_firma', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('colaborador.resoluciones-firmadas.index', compact('resoluciones'));
    }

    /**
     * Mostrar formulario de firma
     */
    public function mostrarFormularioFirma(ColaFirma $colaFirma)
    {
        // Verificar que el usuario sea el firmante
        if ($colaFirma->id_usuario_firmante !== Auth::id()) {
            abort(403, 'No tiene permisos para firmar esta resolución');
        }

        // Verificar que esté pendiente
        $estadoPendiente = EstadoFirma::where('nombre_estado', 'Pendiente')->first();
        if ($colaFirma->id_estado_firma !== $estadoPendiente?->id_estado_firma) {
            return redirect()
                ->back()
                ->with('error', '❌ Esta solicitud de firma ya no está pendiente');
        }

        $colaFirma->load([
            'resolucion.tipoResolucion',
            'resolucion.usuarioCreador.persona',
            'usuarioSolicita.persona',
        ]);

        return view('colaborador.resoluciones.firmar', compact('colaFirma'));
    }

    /**
     * Procesar firma digital
     */
    public function firmar(Request $request, ColaFirma $colaFirma)
    {
        // Verificar que el usuario sea el firmante
        if ($colaFirma->id_usuario_firmante !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'metodo_firma' => 'required|in:firma_peru,certificado_local,token_usb',
            'archivo_firmado' => 'required|file|mimes:pdf|max:15360', // 15MB
            'certificado_digital' => 'nullable|array',
            'observaciones' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $resolucion = $colaFirma->resolucion;

            // Subir archivo firmado
            $archivo = $request->file('archivo_firmado');
            $nombreArchivo = 'firmado_' . time() . '_' . $archivo->getClientOriginalName();
            $pathFirmado = $archivo->storeAs('resoluciones/firmadas', $nombreArchivo, 'public');

            // Calcular hash del documento antes de firma
            $hashAntes = null;
            if ($resolucion->archivo_resolucion && Storage::disk('public')->exists($resolucion->archivo_resolucion)) {
                $contenidoAntes = Storage::disk('public')->get($resolucion->archivo_resolucion);
                $hashAntes = hash('sha256', $contenidoAntes);
            }

            // Calcular hash del documento firmado
            $contenidoFirmado = Storage::disk('public')->get($pathFirmado);
            $hashFirmado = hash('sha256', $contenidoFirmado);

            // Actualizar resolución
            $resolucion->update([
                'archivo_firmado' => $pathFirmado,
                'id_usuario_firma' => Auth::id(),
                'fecha_firma' => now(),
            ]);

            // Actualizar estado de la solicitud de firma
            $estadoFirmado = EstadoFirma::where('nombre_estado', 'Firmado')->first();
            $colaFirma->update([
                'id_estado_firma' => $estadoFirmado?->id_estado_firma,
                'fecha_firma' => now(),
                'observaciones' => $request->observaciones,
            ]);

            // Registrar en historial de firmas
            HistorialFirma::create([
                'id_resolucion' => $resolucion->id_resolucion,
                'id_usuario' => Auth::id(),
                'metodo_firma' => $request->metodo_firma,
                'certificado_digital' => $request->certificado_digital,
                'hash_documento' => $hashAntes,
                'hash_firmado' => $hashFirmado,
                'ip_firmante' => $request->ip(),
                'archivo_antes_firma' => $resolucion->archivo_resolucion,
                'archivo_despues_firma' => $pathFirmado,
            ]);

            // Si usó Firma Perú, registrar datos adicionales
            if ($request->metodo_firma === 'firma_peru') {
                $this->procesarFirmaPeru($colaFirma, $request);
            }

            DB::commit();

            return redirect()
                ->route('colaborador.resoluciones.show', $resolucion)
                ->with('success', '✅ Resolución firmada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            // Eliminar archivo si se subió
            if (isset($pathFirmado)) {
                Storage::disk('public')->delete($pathFirmado);
            }

            return redirect()
                ->back()
                ->with('error', '❌ Error al firmar resolución: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar solicitud de firma
     */
    public function rechazar(Request $request, ColaFirma $colaFirma)
    {
        // Verificar que el usuario sea el firmante
        if ($colaFirma->id_usuario_firmante !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'motivo_rechazo' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $estadoRechazado = EstadoFirma::where('nombre_estado', 'Rechazado')->first();
            
            $colaFirma->update([
                'id_estado_firma' => $estadoRechazado?->id_estado_firma,
                'motivo_rechazo' => $request->motivo_rechazo,
                'fecha_rechazo' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('colaborador.dashboard')
                ->with('success', '✅ Solicitud de firma rechazada');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', '❌ Error al rechazar firma: ' . $e->getMessage());
        }
    }

    /**
     * Solicitar firma a otro usuario
     */
    public function solicitarFirma(Request $request, Resolucion $resolucion)
    {
        $request->validate([
            'id_usuario_firmante' => 'required|exists:users,id',
            'prioridad' => 'required|in:baja,media,alta',
            'fecha_limite' => 'required|date|after:today',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Verificar que la resolución no esté ya firmada
        if ($resolucion->archivo_firmado) {
            return redirect()
                ->back()
                ->with('error', '❌ Esta resolución ya está firmada');
        }

        DB::beginTransaction();
        try {
            $estadoPendiente = EstadoFirma::where('nombre_estado', 'Pendiente')->first();

            ColaFirma::create([
                'id_resolucion' => $resolucion->id_resolucion,
                'id_usuario_solicita' => Auth::id(),
                'id_usuario_firmante' => $request->id_usuario_firmante,
                'id_estado_firma' => $estadoPendiente?->id_estado_firma,
                'fecha_solicitud' => now(),
                'fecha_limite' => $request->fecha_limite,
                'prioridad' => $request->prioridad,
                'observaciones' => $request->observaciones,
            ]);

            // Cambiar estado de la resolución a "Pendiente de Firma"
            $estadoPendienteFirma = \App\Models\Estado::where('nombre_estado', 'Pendiente de Firma')->first();
            if ($estadoPendienteFirma) {
                $resolucion->update(['id_estado' => $estadoPendienteFirma->id_estado]);
            }

            DB::commit();

            return redirect()
                ->route('colaborador.resoluciones.show', $resolucion)
                ->with('success', '✅ Solicitud de firma enviada');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', '❌ Error al solicitar firma: ' . $e->getMessage());
        }
    }

    /**
     * Ver historial de firmas de una resolución
     */
    public function historial(Resolucion $resolucion)
    {
        $resolucion->load([
            'historialFirmas.usuario.persona',
            'colaFirmas.usuarioFirmante.persona',
            'colaFirmas.usuarioSolicita.persona',
            'colaFirmas.estadoFirma',
        ]);

        return view('colaborador.resoluciones.historial-firmas', compact('resolucion'));
    }

    /**
     * Verificar firma digital
     */
    public function verificarFirma(Resolucion $resolucion)
    {
        if (!$resolucion->archivo_firmado) {
            return response()->json([
                'success' => false,
                'message' => 'La resolución no está firmada',
            ], 404);
        }

        $ultimaFirma = $resolucion->historialFirmas()->latest()->first();

        if (!$ultimaFirma) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró historial de firma',
            ], 404);
        }

        // Calcular hash actual del archivo firmado
        $contenido = Storage::disk('public')->get($resolucion->archivo_firmado);
        $hashActual = hash('sha256', $contenido);

        $esValida = $hashActual === $ultimaFirma->hash_firmado;

        return response()->json([
            'success' => true,
            'valida' => $esValida,
            'firmante' => $ultimaFirma->usuario->persona->nombre_completo ?? 'Desconocido',
            'fecha_firma' => $resolucion->fecha_firma?->format('d/m/Y H:i:s'),
            'metodo_firma' => $ultimaFirma->metodo_firma,
            'certificado' => $ultimaFirma->certificado_digital,
            'hash_documento' => $ultimaFirma->hash_documento,
            'hash_firmado' => $ultimaFirma->hash_firmado,
            'hash_actual' => $hashActual,
            'mensaje' => $esValida 
                ? '✅ Firma digital válida' 
                : '⚠️ El documento ha sido modificado después de la firma',
        ]);
    }

    /**
     * Procesar firma con Firma Perú (simulado)
     */
    private function procesarFirmaPeru(ColaFirma $colaFirma, Request $request)
    {
        // Aquí iría la integración real con Firma Perú
        // Por ahora es simulado

        $ultimaFirma = HistorialFirma::where('id_resolucion', $colaFirma->id_resolucion)
            ->latest()
            ->first();

        if ($ultimaFirma) {
            $ultimaFirma->update([
                'estado_firmaperu' => 'completado',
                'fecha_envio_firmaperu' => now(),
                'fecha_respuesta_firmaperu' => now(),
                'respuesta_firmaperu' => json_encode([
                    'status' => 'success',
                    'transaction_id' => 'FP-' . strtoupper(uniqid()),
                    'timestamp' => now()->toIso8601String(),
                    'certificado' => [
                        'issuer' => 'CN=RENIEC,O=RENIEC,C=PE',
                        'subject' => 'CN=' . Auth::user()->name . ',C=PE',
                        'serial_number' => strtoupper(bin2hex(random_bytes(10))),
                        'valid_from' => now()->subYear()->format('Y-m-d'),
                        'valid_to' => now()->addYears(2)->format('Y-m-d'),
                    ],
                ]),
            ]);
        }
    }
}