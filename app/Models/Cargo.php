<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargo';
    protected $primaryKey = 'id_cargos';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_cargo',
        'descripcion',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_cargos', 'id_cargos');
    }

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }
}