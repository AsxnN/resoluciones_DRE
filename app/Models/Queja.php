<?php
// filepath: app/Models/Queja.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queja extends Model
{
    use HasFactory;

    protected $table = 'quejas';
    protected $primaryKey = 'id_queja';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_cliente',
        'id_resolucion',
        'tipo_queja',
        'descripcion',
        'estado',
        'id_usuario_atiende',
        'respuesta',
        'fecha_respuesta',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'fecha_respuesta' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion');
    }

    public function usuarioAtiende()
    {
        return $this->belongsTo(User::class, 'id_usuario_atiende');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnRevision($query)
    {
        return $query->where('estado', 'en_revision');
    }

    public function scopeResueltas($query)
    {
        return $query->where('estado', 'resuelta');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_queja', $tipo);
    }

    // ========================================
    // MÉTODOS DE NEGOCIO
    // ========================================

    public function atender(int $idUsuario, string $respuesta): bool
    {
        $this->id_usuario_atiende = $idUsuario;
        $this->respuesta = $respuesta;
        $this->fecha_respuesta = now();
        $this->estado = 'resuelta';
        
        return $this->save();
    }

    public function rechazar(int $idUsuario, string $motivo): bool
    {
        $this->id_usuario_atiende = $idUsuario;
        $this->respuesta = $motivo;
        $this->fecha_respuesta = now();
        $this->estado = 'rechazada';
        
        return $this->save();
    }
}