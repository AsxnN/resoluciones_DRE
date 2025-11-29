<?php
// filepath: app/Models/Persona.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'persona';
    protected $primaryKey = 'id_persona';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'tipo_persona',
        'tipo_documento',
        'num_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'telefono',
        'whatsapp',
        'direccion',
        'datos_completos',
        'i_active',
    ];

    protected $casts = [
        'datos_completos' => 'boolean',
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function user()
    {
        return $this->hasOne(User::class, 'id_persona', 'id_persona');
    }

    public function colaborador()
    {
        return $this->hasOne(Colaborador::class, 'id_persona', 'id_persona');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_persona', 'id_persona');
    }

    public function resoluciones()
    {
        return $this->belongsToMany(
            Resolucion::class,
            'persona_resolucion',
            'id_persona',
            'id_resolucion'
        )->withPivot('tipo_relacion', 'i_notificado', 'fecha_notificacion', 'i_active')
          ->withTimestamps();
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopeColaboradores($query)
    {
        return $query->where('tipo_persona', 'colaborador');
    }

    public function scopeClientes($query)
    {
        return $query->where('tipo_persona', 'cliente');
    }

    public function scopeBuscar($query, $busqueda)
    {
        return $query->where(function($q) use ($busqueda) {
            $q->where('num_documento', 'like', "%{$busqueda}%")
              ->orWhere('nombres', 'like', "%{$busqueda}%")
              ->orWhere('apellido_paterno', 'like', "%{$busqueda}%")
              ->orWhere('apellido_materno', 'like', "%{$busqueda}%")
              ->orWhere('correo', 'like', "%{$busqueda}%");
        });
    }

    // ========================================
    // ACCESORIOS
    // ========================================

    public function getNombreCompletoAttribute(): string
    {
        $nombreCompleto = trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
        return $nombreCompleto ?: 'Sin nombre';
    }

    public function getEsColaboradorAttribute(): bool
    {
        return $this->tipo_persona === 'colaborador';
    }

    public function getEsClienteAttribute(): bool
    {
        return $this->tipo_persona === 'cliente';
    }
}