<?php
// filepath: app/Models/HistorialFirma.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialFirma extends Model
{
    use HasFactory;

    protected $table = 'historial_firma';
    protected $primaryKey = 'id_historial_firma';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_resolucion',
        'id_usuario',
        'metodo_firma',
        'certificado_digital',
        'hash_documento',
        'hash_firmado',
        'ip_firmante',
        'archivo_antes_firma',
        'archivo_despues_firma',
        'estado_firmaperu',
        'respuesta_firmaperu',
        'fecha_envio_firmaperu',
        'fecha_respuesta_firmaperu',
    ];

    protected $casts = [
        'certificado_digital' => 'array',
        'fecha_creacion' => 'datetime',
        'fecha_envio_firmaperu' => 'datetime',
        'fecha_respuesta_firmaperu' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePorResolucion($query, $idResolucion)
    {
        return $query->where('id_resolucion', $idResolucion);
    }

    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopeFirmaPeru($query)
    {
        return $query->where('metodo_firma', 'firma_peru');
    }

    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('fecha_creacion', '>=', now()->subDays($dias));
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    public function verificarIntegridad(): bool
    {
        if (!$this->archivo_despues_firma || !$this->hash_firmado) {
            return false;
        }

        $hashActual = hash_file('sha256', storage_path('app/' . $this->archivo_despues_firma));
        
        return $hashActual === $this->hash_firmado;
    }
}