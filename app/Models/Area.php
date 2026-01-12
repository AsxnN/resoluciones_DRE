<?php
// filepath: app/Models/Area.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Area extends Model
{
    use HasFactory;

    protected $table = 'area';
    protected $primaryKey = 'id_area';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_area',
        'descripcion',
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
     * Relación con Colaboradores
     */
    public function colaboradores(): HasMany
    {
        return $this->hasMany(Colaborador::class, 'id_area', 'id_area');
    }

    /**
     * Relación con Usuario que creó/modificó
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope para áreas activas
     */
    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscar($query, $search)
    {
        return $query->where('nombre_area', 'like', "%{$search}%");
    }
}