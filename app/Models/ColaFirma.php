<?php
// filepath: app/Models/ColaFirma.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColaFirma extends Model
{
    use HasFactory;

    protected $table = 'cola_firma';
    protected $primaryKey = 'id_cola_firma';
    
    const CREATED_AT = 'fecha_solicitud';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_resolucion',
        'id_usuario_solicita',
        'id_usuario_firmante',
        'id_estado_firma',
        'prioridad',
        'fecha_limite',
        'fecha_firma',
        'observaciones',
        'motivo_rechazo',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_limite' => 'datetime',
        'fecha_firma' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion');
    }

    public function usuarioSolicita()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicita');
    }

    public function usuarioFirmante()
    {
        return $this->belongsTo(User::class, 'id_usuario_firmante');
    }

    public function estadoFirma()
    {
        return $this->belongsTo(EstadoFirma::class, 'id_estado_firma');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePendientes($query)
    {
        return $query->whereHas('estadoFirma', function($q) {
            $q->where('nombre_estado', 'Pendiente');
        });
    }

    public function scopeFirmadas($query)
    {
        return $query->whereHas('estadoFirma', function($q) {
            $q->where('nombre_estado', 'Firmado');
        });
    }

    public function scopeRechazadas($query)
    {
        return $query->whereHas('estadoFirma', function($q) {
            $q->where('nombre_estado', 'Rechazado');
        });
    }

    public function scopePorPrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    public function scopePorUsuarioFirmante($query, $idUsuario)
    {
        return $query->where('id_usuario_firmante', $idUsuario);
    }

    public function scopeExpiradas($query)
    {
        return $query->where('fecha_limite', '<', now())
                     ->pendientes();
    }

    public function scopeConRelaciones($query)
    {
        return $query->with([
            'resolucion.estado',
            'resolucion.tipoResolucion',
            'usuarioSolicita.persona',
            'usuarioFirmante.persona',
            'estadoFirma'
        ]);
    }

    // ========================================
    // ACCESORIOS
    // ========================================

    public function getEstaVencidaAttribute(): bool
    {
        return $this->fecha_limite && $this->fecha_limite->isPast() && !$this->fecha_firma;
    }

    public function getDiasRestantesAttribute(): ?int
    {
        if (!$this->fecha_limite || $this->fecha_firma) {
            return null;
        }

        return max(0, now()->diffInDays($this->fecha_limite, false));
    }

    public function getColorPrioridadAttribute(): string
    {
        return match($this->prioridad) {
            'alta' => 'red',
            'media' => 'yellow',
            'baja' => 'blue',
            default => 'gray'
        };
    }

    // ========================================
    // MÉTODOS DE NEGOCIO
    // ========================================

    public function firmar(int $idUsuario): bool
    {
        $estadoFirmado = EstadoFirma::where('nombre_estado', 'Firmado')->first();
        
        if (!$estadoFirmado || $this->id_usuario_firmante !== $idUsuario) {
            return false;
        }

        $this->id_estado_firma = $estadoFirmado->id_estado_firma;
        $this->fecha_firma = now();
        
        return $this->save();
    }

    public function rechazar(int $idUsuario, string $motivo): bool
    {
        $estadoRechazado = EstadoFirma::where('nombre_estado', 'Rechazado')->first();
        
        if (!$estadoRechazado || $this->id_usuario_firmante !== $idUsuario) {
            return false;
        }

        $this->id_estado_firma = $estadoRechazado->id_estado_firma;
        $this->motivo_rechazo = $motivo;
        
        return $this->save();
    }
}