<?php
// filepath: app/Models/Permiso.php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permiso extends SpatiePermission
{
    protected $table = 'permissions';

    protected $fillable = [
        'id_modulo',
        'name',
        'slug',
        'guard_name',
        'descripcion',
        'i_active',
        'tipo_permiso',
    ];

    protected $casts = [
        'i_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id_modulo');
    }

    public function metadata()
    {
        return $this->hasMany(UsuarioPermisoMetadata::class, 'permission_id');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopePorModulo($query, $idModulo)
    {
        return $query->where('id_modulo', $idModulo);
    }

    public function scopeCrud($query)
    {
        return $query->where('tipo_permiso', 'crud');
    }

    public function scopeEspecial($query)
    {
        return $query->where('tipo_permiso', 'especial');
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    public function esCrud(): bool
    {
        return $this->tipo_permiso === 'crud';
    }

    public function esEspecial(): bool
    {
        return $this->tipo_permiso === 'especial';
    }

    public function obtenerAccion(): string
    {
        // Extraer acción del nombre: "resoluciones.ver" → "ver"
        return explode('.', $this->name)[1] ?? '';
    }
}