<?php
// filepath: app/Models/TipoResolucion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoResolucion extends Model
{
    use HasFactory;

    protected $table = 'tipo_resolucion';
    protected $primaryKey = 'id_tipo_resolucion';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_tipo_resolucion',
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

    public function resoluciones()
    {
        return $this->hasMany(Resolucion::class, 'id_tipo_resolucion');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_usuario');
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
        return $query->where('nombre_tipo_resolucion', 'like', "%{$nombre}%");
    }
}