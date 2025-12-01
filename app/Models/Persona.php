<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_persona', 'id_persona');
    }

    public function colaborador(): HasOne
    {
        return $this->hasOne(Colaborador::class, 'id_persona', 'id_persona');
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id_persona', 'id_persona');
    }

    /**
     * Relaciones delegadas desde colaborador (si es colaborador)
     */
    public function direccion()
    {
        return $this->hasOneThrough(
            Direccion::class,
            Colaborador::class,
            'id_persona',      // FK en colaborador
            'id_direcciones',  // PK en direccion
            'id_persona',      // PK en persona
            'id_direcciones'   // FK en colaborador
        );
    }

    public function dependencia()
    {
        return $this->hasOneThrough(
            Dependencia::class,
            Colaborador::class,
            'id_persona',
            'id_dependencias',
            'id_persona',
            'id_dependencia'
        );
    }

    public function area()
    {
        return $this->hasOneThrough(
            Area::class,
            Colaborador::class,
            'id_persona',
            'id_area',
            'id_persona',
            'id_area'
        );
    }

    public function cargo()
    {
        return $this->hasOneThrough(
            Cargo::class,
            Colaborador::class,
            'id_persona',
            'id_cargos',
            'id_persona',
            'id_cargos'
        );
    }

    public function especialidad()
    {
        return $this->hasOneThrough(
            Especialidad::class,
            Colaborador::class,
            'id_persona',
            'id_especialidad',
            'id_persona',
            'id_especialidad'
        );
    }

    public function tipoPersonal()
    {
        return $this->hasOneThrough(
            TipoPersonal::class,
            Colaborador::class,
            'id_persona',
            'id_tipo_personal',
            'id_persona',
            'id_tipo_personal'
        );
    }

    public function resoluciones(): BelongsToMany
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
    // ACCESSORS
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