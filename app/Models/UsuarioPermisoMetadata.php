<?php
// filepath: app/Models/UsuarioPermisoMetadata.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioPermisoMetadata extends Model
{
    use HasFactory;

    protected $table = 'usuario_permisos_metadata';
    protected $primaryKey = 'id_metadata';
    
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'permission_id',
        'id_usuario_asigna',
        'observacion',
        'fecha_asignacion',
        'fecha_revocacion',
        'i_active',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_revocacion' => 'datetime',
        'i_active' => 'boolean',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'permission_id');
    }

    public function usuarioAsigna()
    {
        return $this->belongsTo(User::class, 'id_usuario_asigna');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopeRevocados($query)
    {
        return $query->where('i_active', false);
    }

    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }
}