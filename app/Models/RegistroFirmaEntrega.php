<?php
// filepath: app/Models/RegistroFirmaEntrega.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RegistroFirmaEntrega extends Model
{
    use HasFactory;

    protected $table = 'registro_firma_entrega';
    protected $primaryKey = 'id_registro_firma_entrega';

    protected $fillable = [
        'id_resolucion',
        'id_persona_resolucion_datos',
        'id_usuario_firmante',
        'id_usuario_solicita',
        'firmado',
        'fecha_solicitud',
        'fecha_firma',
        'fecha_entrega',
        'archivo_firmado_entrega',
        'codigo_verificacion',
        'observaciones',
        'correo_destino',
        'metadata_firma_peru',
    ];

    protected $casts = [
        'firmado' => 'boolean',
        'fecha_solicitud' => 'datetime',
        'fecha_firma' => 'datetime',
        'fecha_entrega' => 'datetime',
        'metadata_firma_peru' => 'array',
    ];

    // ========================================
    // BOOT
    // ========================================
    
    protected static function boot()
    {
        parent::boot();
        
        // Generar código de verificación único al crear
        static::creating(function ($registro) {
            if (empty($registro->codigo_verificacion)) {
                $registro->codigo_verificacion = strtoupper(Str::random(10) . '-' . now()->timestamp);
            }
        });
    }

    // ========================================
    // RELACIONES
    // ========================================

    /**
     * Resolución que se está entregando
     */
    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion');
    }

    /**
     * Persona externa destinataria
     */
    public function personaExterna()
    {
        return $this->belongsTo(PersonaResolucionDatos::class, 'id_persona_resolucion_datos');
    }

    /**
     * Usuario que firma (trabajador DRE)
     */
    public function usuarioFirmante()
    {
        return $this->belongsTo(User::class, 'id_usuario_firmante');
    }

    /**
     * Usuario que solicita la firma
     */
    public function usuarioSolicita()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicita');
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Registros firmados
     */
    public function scopeFirmados($query)
    {
        return $query->where('firmado', true);
    }

    /**
     * Registros pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('firmado', false);
    }

    /**
     * Registros de una resolución específica
     */
    public function scopeDeResolucion($query, $idResolucion)
    {
        return $query->where('id_resolucion', $idResolucion);
    }

    /**
     * Registros de una persona externa específica
     */
    public function scopeDePersona($query, $idPersona)
    {
        return $query->where('id_persona_resolucion_datos', $idPersona);
    }

    /**
     * Registros entregados (tienen fecha de entrega)
     */
    public function scopeEntregados($query)
    {
        return $query->whereNotNull('fecha_entrega');
    }

    // ========================================
    // ACCESSORS & MUTATORS
    // ========================================

    /**
     * Estado visual del registro
     */
    public function getEstadoVisualAttribute(): string
    {
        if ($this->fecha_entrega) {
            return 'Entregado';
        }
        
        if ($this->firmado && $this->fecha_firma) {
            return 'Firmado - Pendiente entrega';
        }
        
        return 'Pendiente firma';
    }

    /**
     * Color del badge según estado
     */
    public function getColorEstadoAttribute(): string
    {
        if ($this->fecha_entrega) {
            return 'green';
        }
        
        if ($this->firmado) {
            return 'blue';
        }
        
        return 'yellow';
    }

    // ========================================
    // MÉTODOS
    // ========================================

    /**
     * Marcar como firmado (manual hasta integrar Firma Perú)
     */
    public function marcarComoFirmado(int $idUsuarioFirmante, ?string $archivoFirmado = null, ?string $observaciones = null): bool
    {
        $this->firmado = true;
        $this->fecha_firma = now();
        $this->id_usuario_firmante = $idUsuarioFirmante;
        
        if ($archivoFirmado) {
            $this->archivo_firmado_entrega = $archivoFirmado;
        }
        
        if ($observaciones) {
            $this->observaciones = $observaciones;
        }
        
        return $this->save();
    }

    /**
     * Registrar entrega al cliente
     */
    public function registrarEntrega(?string $correoDestino = null, ?string $observaciones = null): bool
    {
        if (!$this->firmado) {
            return false;
        }
        
        $this->fecha_entrega = now();
        
        if ($correoDestino) {
            $this->correo_destino = $correoDestino;
        }
        
        if ($observaciones) {
            $this->observaciones = ($this->observaciones ? $this->observaciones . "\n" : '') . $observaciones;
        }
        
        return $this->save();
    }

    /**
     * Verificar si puede ser firmado
     */
    public function puedeFirmarse(): bool
    {
        return !$this->firmado && $this->resolucion->archivo_resolucion;
    }

    /**
     * Verificar si puede ser entregado
     */
    public function puedeEntregarse(): bool
    {
        return $this->firmado && !$this->fecha_entrega;
    }
}