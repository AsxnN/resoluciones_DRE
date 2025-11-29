<?php
// filepath: app/Models/Modulo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'modulos';
    protected $primaryKey = 'id_modulo';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_modulo',
        'slug',
        'descripcion',
        'ruta',
        'icono',
        'orden',
        'tipo_modulo',
        'i_active',
    ];

    protected $casts = [
        'orden' => 'integer',
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_modulo', 'id_modulo');
    }

    public function permisosActivos()
    {
        return $this->hasMany(Permiso::class, 'id_modulo', 'id_modulo')
                    ->where('i_active', true);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden');
    }

    public function scopeAdmin($query)
    {
        return $query->where('tipo_modulo', 'admin');
    }

    public function scopeColaborador($query)
    {
        return $query->where('tipo_modulo', 'colaborador');
    }

    public function scopeCompartido($query)
    {
        return $query->where('tipo_modulo', 'compartido');
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    public function esAdmin(): bool
    {
        return $this->tipo_modulo === 'admin';
    }

    public function esColaborador(): bool
    {
        return $this->tipo_modulo === 'colaborador';
    }

    public function esCompartido(): bool
    {
        return $this->tipo_modulo === 'compartido';
    }
}