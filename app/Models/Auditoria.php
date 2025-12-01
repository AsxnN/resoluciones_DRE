<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla_afectada',
        'id_registro',
        'descripcion',
        'datos_anteriores',
        'datos_nuevos',
        'ip_address',
        'user_agent',
        'fecha_accion',
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
        'fecha_accion' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'id_auditoria';
    }

    // ========================================
    // RELACIONES
    // ========================================

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    // ========================================
    // ACCESSORS (Solo los que tienen sentido)
    // ========================================

    public function getAccionFormateadaAttribute(): string
    {
        return match($this->accion) {
            'crear' => 'Creación',
            'editar' => 'Edición',
            'eliminar' => 'Eliminación',
            'login' => 'Inicio de Sesión',
            'logout' => 'Cierre de Sesión',
            'actualizar' => 'Actualización',
            default => ucfirst(str_replace('_', ' ', $this->accion))
        };
    }

    // ========================================
    // MÉTODOS ESTÁTICOS
    // ========================================

    public static function registrarLogin(int $idUsuario): void
    {
        self::create([
            'id_usuario' => $idUsuario,
            'accion' => 'login',
            'descripcion' => "Inicio de sesión exitoso",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }

    public static function registrarLogout(): void
    {
        self::create([
            'id_usuario' => auth()->id(),
            'accion' => 'logout',
            'descripcion' => 'Cierre de sesión',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }

    public static function registrarActualizacion(string $tabla, int $idRegistro, array $datosAnteriores, array $datosNuevos, string $descripcion): void
    {
        self::create([
            'id_usuario' => auth()->id(),
            'accion' => 'actualizar',
            'tabla_afectada' => $tabla,
            'id_registro' => $idRegistro,
            'descripcion' => $descripcion,
            'datos_anteriores' => $datosAnteriores,
            'datos_nuevos' => $datosNuevos,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fecha_accion' => now(),
        ]);
    }
}