<?php
// filepath: app/Models/EstadoFirma.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoFirma extends Model
{
    use HasFactory;

    protected $table = 'estados_firma';
    protected $primaryKey = 'id_estado_firma';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = null;

    protected $fillable = [
        'nombre_estado',
        'descripcion',
        'color',
        'i_active',
    ];

    protected $casts = [
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function colaFirmas()
    {
        return $this->hasMany(ColaFirma::class, 'id_estado_firma');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre_estado', $nombre);
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    public static function obtenerIdPorNombre(string $nombre): ?int
    {
        return self::where('nombre_estado', $nombre)->value('id_estado_firma');
    }

    public function esPendiente(): bool
    {
        return $this->nombre_estado === 'Pendiente';
    }

    public function esFirmado(): bool
    {
        return $this->nombre_estado === 'Firmado';
    }

    public function esRechazado(): bool
    {
        return $this->nombre_estado === 'Rechazado';
    }

    public function getColorBadgeAttribute(): string
    {
        return match($this->color) {
            '#10b981' => 'green',
            '#ef4444' => 'red',
            '#f59e0b' => 'yellow',
            '#3b82f6' => 'blue',
            default => 'gray'
        };
    }
}