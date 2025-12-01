<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $primaryKey = 'id_modulo';

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
        'i_active' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Relación con permisos
     */
    public function permisos(): HasMany
    {
        return $this->hasMany(Permiso::class, 'id_modulo', 'id_modulo')
                    ->where('i_active', true)
                    ->orderBy('name');
    }

    /**
     * Scope para módulos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('i_active', true)->orderBy('orden');
    }

    /**
     * Obtener cantidad de permisos
     */
    public function getCantidadPermisosAttribute(): int
    {
        return $this->permisos()->count();
    }
}