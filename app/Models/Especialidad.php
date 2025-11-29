<?php
// filepath: app/Models/Especialidad.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    protected $table = 'especialidad';
    protected $primaryKey = 'id_especialidad';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_especialidad',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
    ];

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_especialidad');
    }

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }
}