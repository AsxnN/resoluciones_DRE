<?php
// filepath: app/Models/Auditoria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';
    
    const CREATED_AT = 'fecha_accion';
    const UPDATED_AT = null;

    protected $fillable = [
        'tabla_afectada',
        'id_registro',
        'accion',
        'datos_anteriores',
        'datos_nuevos',
        'id_usuario',
        'ip_address',
        'user_agent',
        'descripcion',
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
        'fecha_accion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePorTabla($query, $tabla)
    {
        return $query->where('tabla_afectada', $tabla);
    }

    public function scopePorAccion($query, $accion)
    {
        return $query->where('accion', $accion);
    }

    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('fecha_accion', '>=', now()->subDays($dias));
    }
}