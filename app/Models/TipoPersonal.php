<?php
// filepath: app/Models/TipoPersonal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPersonal extends Model
{
    use HasFactory;

    protected $table = 'tipo_personal';
    protected $primaryKey = 'id_tipo_personal';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_tipo_personal',
        'i_active',
    ];

    protected $casts = [
        'i_active' => 'boolean',
    ];

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_tipo_personal');
    }

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function getRouteKeyName()
    {
        return 'id_tipo_personal'; // o el nombre de tu primary key
    }
}