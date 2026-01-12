<?php
// filepath: app/Models/Direccion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direccion';
    protected $primaryKey = 'id_direcciones';
    public $timestamps = false;

    protected $fillable = [
        'nombre_direcciones',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    /**
     * Relación con Colaboradores
     */
    public function colaboradores(): HasMany
    {
        return $this->hasMany(Colaborador::class, 'id_direcciones', 'id_direcciones');
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
     * Scope para direcciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('i_active', true);
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscar($query, $search)
    {
        return $query->where('nombre_direcciones', 'like', "%{$search}%");
    }
}