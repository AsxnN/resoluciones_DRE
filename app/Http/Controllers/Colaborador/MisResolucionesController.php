<?php
// filepath: app/Http/Controllers/Colaborador/MisResolucionesController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\Resolucion;
use App\Models\Estado;
use App\Models\TipoResolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MisResolucionesController extends Controller
{
    /**
     * Mostrar resoluciones según el tipo de acceso del usuario
     * - Colaboradores/Admin: ven resoluciones donde están relacionados (es_interna=true)
     * - Clientes externos: solo ven resoluciones que se les hayan entregado (tabla entrega_resolucion) y firmadas
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $tipoAcceso = $user->tipo_acceso; // 'colaborador', 'admin', 'cliente'
        
        // Query base con relaciones
        $query = Resolucion::with([
            'estado',
            'tipoResolucion',
            'usuarioCreador.persona',
            'usuarioFirmante.persona'
        ]);

        // Filtrar según tipo de usuario
        if ($tipoAcceso === 'cliente') {
            // EXTERNOS: solo resoluciones que se le hayan entregado realmente
            $query->whereHas('entregas.personaEntrega.user', function($q) use ($userId) {
                $q->where('id', $userId);
            })->whereNotNull('archivo_firmado');
        } else {
            // INTERNOS (colaborador/admin): Todas las relacionadas con es_interna=true
            $query->whereHas('personasRelacionadas', function($q) use ($userId) {
                $q->where('persona_resolucion_datos.id_user', $userId)
                  ->where('persona_resolucion_datos.es_interna', true);
            });
        }

        // Filtros adicionales
        if ($request->filled('estado')) {
            $query->where('id_estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('id_tipo_resolucion', $request->tipo);
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

        // Datos para filtros
        $estados = Estado::all();
        $tiposResolucion = TipoResolucion::where('i_active', true)->get();

        // Pasar tipo de acceso a la vista
        return view('colaborador.mis-resoluciones.index', compact(
            'resoluciones', 
            'estados', 
            'tiposResolucion',
            'tipoAcceso'
        ));
    }

    /**
     * Mostrar detalles de una resolución
     */
    public function show(Resolucion $resolucion)
    {
        $user = Auth::user();
        $userId = $user->id;
        $tipoAcceso = $user->tipo_acceso;

        // Verificar acceso según tipo de usuario
        if ($tipoAcceso === 'cliente') {
            // Externos: solo si se le entregó realmente y la resolución está firmada
            $tieneAcceso = $resolucion->entregas()
                ->whereHas('personaEntrega.user', fn($q) => $q->where('id', $userId))
                ->exists() && $resolucion->archivo_firmado;
        } else {
            // Internos: si está relacionado con es_interna=true
            $tieneAcceso = $resolucion->personasRelacionadas()
                ->where('persona_resolucion_datos.id_user', $userId)
                ->where('persona_resolucion_datos.es_interna', true)
                ->exists();
        }

        if (!$tieneAcceso) {
            abort(403, 'No tiene acceso a esta resolución');
        }

        $resolucion->load([
            'estado',
            'tipoResolucion',
            'usuarioCreador.persona',
            'usuarioFirmante.persona',
            'personasRelacionadas.usuario.persona',
            'colaFirmas.estadoFirma',
            'historialFirmas.usuario.persona',
        ]);

        return view('colaborador.mis-resoluciones.show', compact('resolucion', 'tipoAcceso'));
    }

    /**
     * Descargar archivo de resolución
     * - Clientes: solo archivo_firmado
     * - Colaboradores: archivo_firmado o archivo_resolucion
     */
    public function descargar(Resolucion $resolucion)
    {
        $user = Auth::user();
        $userId = $user->id;
        $tipoAcceso = $user->tipo_acceso;

        // Verificar acceso (reutilizamos la misma lógica de show)
        if ($tipoAcceso === 'cliente') {
            $tieneAcceso = $resolucion->entregas()
                ->whereHas('personaEntrega.user', fn($q) => $q->where('id', $userId))
                ->exists() && $resolucion->archivo_firmado;
        } else {
            $tieneAcceso = $resolucion->personasRelacionadas()
                ->where('persona_resolucion_datos.id_user', $userId)
                ->where('persona_resolucion_datos.es_interna', true)
                ->exists();
        }

        if (!$tieneAcceso) {
            abort(403, 'No tiene acceso a esta resolución');
        }

        // Determinar qué archivo descargar
        if ($tipoAcceso === 'cliente') {
            // Clientes solo pueden descargar el archivo firmado
            if (!$resolucion->archivo_firmado) {
                abort(404, 'Archivo firmado no disponible');
            }
            $archivo = $resolucion->archivo_firmado;
            $nombreArchivo = "RES_FIRMADA_{$resolucion->num_resolucion}.pdf";
        } else {
            // Colaboradores pueden descargar firmado o el original
            $archivo = $resolucion->archivo_firmado ?? $resolucion->archivo_resolucion;
            if (!$archivo) {
                abort(404, 'Archivo no disponible');
            }
            $tipo = $resolucion->archivo_firmado ? 'FIRMADA' : 'ORIGINAL';
            $nombreArchivo = "RES_{$tipo}_{$resolucion->num_resolucion}.pdf";
        }

        // Verificar que el archivo existe en storage
        if (!Storage::disk('public')->exists($archivo)) {
            abort(404, 'El archivo no existe en el servidor');
        }

        return Storage::disk('public')->download($archivo, $nombreArchivo);
    }
}