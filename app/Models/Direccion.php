<?php
// filepath: app/Models/Direccion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direccion';
    protected $primaryKey = 'id_direcciones';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_direcciones',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
    ];

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_direcciones');
    }

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }
}