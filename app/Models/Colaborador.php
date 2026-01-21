<?php
// filepath: app/Models/Colaborador.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Controllers\Colaborador\RegistroFirmaEntregaController;

class Colaborador extends Model
{
    use HasFactory;

    protected $table = 'colaborador';
    protected $primaryKey = 'id_colab_dis'; // ← CAMBIAR A id_colab_dis
    public $incrementing = false; // ← IMPORTANTE: No es autoincremental
    protected $keyType = 'string'; // ← IMPORTANTE: Es string, no integer
    public $timestamps = false;

    protected $fillable = [
        'id_colab_dis',
        'id_persona',
        'id_cargos',
        'id_unidades',
        'id_direcciones',
        'id_dependencia',
        'id_area',
        'id_especialidad',
        'id_tipo_personal',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    /**
     * Relación con Persona
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    /**
     * Relación con Usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    /**
     * Relación con Cargo
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'id_cargos', 'id_cargos');
    }

    /**
     * Relación con Unidad
     */
    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'id_unidades', 'id_unidad');
    }

    /**
     * Relación con Dirección
     */
    public function direccion(): BelongsTo
    {
        return $this->belongsTo(Direccion::class, 'id_direcciones', 'id_direcciones');
    }

    /**
     * Relación con Dependencia
     */
    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'id_dependencia', 'id_dependencias');
    }

    /**
     * Relación con Área
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    /**
     * Relación con Especialidad
     */
    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class, 'id_especialidad', 'id_especialidad');
    }

    /**
     * Relación con Tipo de Personal
     */
    public function tipoPersonal(): BelongsTo
    {
        return $this->belongsTo(TipoPersonal::class, 'id_tipo_personal', 'id_tipo_personal');
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope para colaboradores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    /**
     * Scope para buscar por nombre de persona
     */
    public function scopeBuscarPorNombre($query, $search)
    {
        return $query->whereHas('persona', function ($q) use ($search) {
            $q->where('nombres', 'like', "%{$search}%")
              ->orWhere('apellido_paterno', 'like', "%{$search}%")
              ->orWhere('apellido_materno', 'like', "%{$search}%");
        });
    }

    /**
     * Scope para buscar por código
     */
    public function scopeBuscarPorCodigo($query, $search)
    {
        return $query->where('id_colab_dis', 'like', "%{$search}%");
    }

    // ========================================
    // ACCESSORS
    // ========================================

    /**
     * Obtener el nombre completo del colaborador
     */
    public function getNombreCompletoAttribute(): string
    {
        if (!$this->persona) {
            return 'Sin nombre';
        }

        return trim(implode(' ', [
            $this->persona->nombres,
            $this->persona->apellido_paterno,
            $this->persona->apellido_materno,
        ]));
    }

    /**
     * Obtener el estado en texto
     */
    public function getEstadoTextoAttribute(): string
    {
        return $this->i_active ? 'Activo' : 'Inactivo';
    }
}