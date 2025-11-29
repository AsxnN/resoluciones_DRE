<?php
// filepath: app/Models/Notificacion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificacion';
    protected $primaryKey = 'id_notificacion';
    
    const CREATED_AT = 'fecha_notificacion';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_resolucion',
        'id_usuario',
        'tipo_notificacion',
        'mensaje',
        'i_leido',
        'fecha_lectura',
    ];

    protected $casts = [
        'i_leido' => 'boolean',
        'fecha_notificacion' => 'datetime',
        'fecha_lectura' => 'datetime',
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

    public function scopeNoLeidas($query)
    {
        return $query->where('i_leido', false);
    }

    public function scopeLeidas($query)
    {
        return $query->where('i_leido', true);
    }

    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_notificacion', $tipo);
    }

    public function scopeRecientes($query, $dias = 7)
    {
        return $query->where('fecha_notificacion', '>=', now()->subDays($dias));
    }

    // ========================================
    // MÉTODOS DE NEGOCIO
    // ========================================

    public function marcarComoLeida(): bool
    {
        if ($this->i_leido) {
            return true;
        }

        $this->i_leido = true;
        $this->fecha_lectura = now();
        
        return $this->save();
    }

    public static function crearNotificacion(
        int $idResolucion,
        int $idUsuario,
        string $tipo,
        string $mensaje
    ): self {
        return self::create([
            'id_resolucion' => $idResolucion,
            'id_usuario' => $idUsuario,
            'tipo_notificacion' => $tipo,
            'mensaje' => $mensaje,
        ]);
    }
}