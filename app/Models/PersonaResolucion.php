<?php
// filepath: app/Models/PersonaResolucion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonaResolucion extends Pivot
{
    use HasFactory;

    protected $table = 'persona_resolucion';
    protected $primaryKey = 'id_persona_resolucion';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_resolucion',
        'id_persona',
        'tipo_relacion',
        'i_notificado',
        'fecha_notificacion',
        'i_active',
    ];

    protected $casts = [
        'i_notificado' => 'boolean',
        'i_active' => 'boolean',
        'fecha_notificacion' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopeInvolucrados($query)
    {
        return $query->where('tipo_relacion', 'involucrado');
    }

    public function scopeNotificados($query)
    {
        return $query->where('tipo_relacion', 'notificado');
    }

    public function scopeFirmantes($query)
    {
        return $query->where('tipo_relacion', 'firmante');
    }

    public function scopePendientesNotificacion($query)
    {
        return $query->where('i_notificado', false);
    }
}