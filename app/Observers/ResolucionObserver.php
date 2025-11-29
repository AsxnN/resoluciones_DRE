<?php
// filepath: app/Observers/ResolucionObserver.php

namespace App\Observers;

use App\Models\Auditoria;
use App\Models\Notificacion;
use App\Models\Resolucion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResolucionObserver
{
    /**
     * Handle the Resolucion "created" event.
     */
    public function created(Resolucion $resolucion): void
    {
        // Registrar en auditoría
        $this->registrarAuditoria($resolucion, 'crear', 'Resolución creada');

        Log::info('Resolución creada', [
            'id' => $resolucion->id_resolucion,
            'numero' => $resolucion->num_resolucion,
            'usuario' => Auth::id(),
        ]);
    }

    /**
     * Handle the Resolucion "updated" event.
     */
    public function updated(Resolucion $resolucion): void
    {
        $cambios = $resolucion->getDirty();
        
        if (empty($cambios)) {
            return;
        }

        // Registrar en auditoría
        $this->registrarAuditoria($resolucion, 'actualizar', 'Resolución actualizada', $cambios);

        // Si cambió el estado, notificar
        if (array_key_exists('id_estado', $cambios)) {
            $this->notificarCambioEstado($resolucion);
        }

        // Si se firmó, notificar
        if (array_key_exists('archivo_firmado', $cambios) && $resolucion->archivo_firmado) {
            $this->notificarFirmado($resolucion);
        }

        Log::info('Resolución actualizada', [
            'id' => $resolucion->id_resolucion,
            'cambios' => array_keys($cambios),
        ]);
    }

    /**
     * Handle the Resolucion "deleted" event.
     */
    public function deleted(Resolucion $resolucion): void
    {
        $this->registrarAuditoria($resolucion, 'eliminar', 'Resolución eliminada');

        Log::warning('Resolución eliminada', [
            'id' => $resolucion->id_resolucion,
            'numero' => $resolucion->num_resolucion,
        ]);
    }

    /**
     * Registrar acción en auditoría
     */
    private function registrarAuditoria(Resolucion $resolucion, string $accion, string $descripcion, array $cambios = []): void
    {
        if (!Auth::check()) {
            return;
        }

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => $accion,
            'tabla_afectada' => 'resolucion',
            'id_registro' => $resolucion->id_resolucion,
            'descripcion' => $descripcion,
            'datos_anteriores' => !empty($cambios) ? json_encode($resolucion->getOriginal()) : null,
            'datos_nuevos' => !empty($cambios) ? json_encode($cambios) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Notificar cambio de estado
     */
    private function notificarCambioEstado(Resolucion $resolucion): void
    {
        // Notificar al creador de la resolución
        if ($resolucion->id_usuario && $resolucion->id_usuario !== Auth::id()) {
            Notificacion::create([
                'id_usuario' => $resolucion->id_usuario,
                'id_resolucion' => $resolucion->id_resolucion,
                'titulo_notificacion' => 'Cambio de estado de resolución',
                'mensaje_notificacion' => "La resolución {$resolucion->num_resolucion} cambió a estado: {$resolucion->estado->nombre_estado}",
                'tipo_notificacion' => 'cambio_estado',
                'i_leido' => false,
            ]);
        }

        // Notificar a personas involucradas
        foreach ($resolucion->personasInvolucradas as $persona) {
            if ($persona->usuario) {
                Notificacion::create([
                    'id_usuario' => $persona->usuario->id,
                    'id_resolucion' => $resolucion->id_resolucion,
                    'titulo_notificacion' => 'Actualización de resolución',
                    'mensaje_notificacion' => "La resolución {$resolucion->num_resolucion} en la que está involucrado cambió de estado a: {$resolucion->estado->nombre_estado}",
                    'tipo_notificacion' => 'actualizacion',
                    'i_leido' => false,
                ]);
            }
        }
    }

    /**
     * Notificar cuando se firma una resolución
     */
    private function notificarFirmado(Resolucion $resolucion): void
    {
        // Notificar al creador
        if ($resolucion->id_usuario && $resolucion->id_usuario !== Auth::id()) {
            Notificacion::create([
                'id_usuario' => $resolucion->id_usuario,
                'id_resolucion' => $resolucion->id_resolucion,
                'titulo_notificacion' => '✅ Resolución firmada',
                'mensaje_notificacion' => "La resolución {$resolucion->num_resolucion} ha sido firmada digitalmente",
                'tipo_notificacion' => 'firma_exitosa',
                'i_leido' => false,
            ]);
        }

        // Notificar a personas involucradas
        foreach ($resolucion->personasInvolucradas as $persona) {
            if ($persona->usuario) {
                Notificacion::create([
                    'id_usuario' => $persona->usuario->id,
                    'id_resolucion' => $resolucion->id_resolucion,
                    'titulo_notificacion' => '📄 Resolución disponible',
                    'mensaje_notificacion' => "La resolución {$resolucion->num_resolucion} ya está firmada y disponible para descarga",
                    'tipo_notificacion' => 'documento_disponible',
                    'i_leido' => false,
                ]);
            }
        }
    }
}