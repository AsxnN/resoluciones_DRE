<?php
// filepath: app/Models/Dependencia.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'dependencia';
    protected $primaryKey = 'id_dependencias';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'cod_dependencia',
        'nombre_dependencia',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
    ];

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_dependencia');
    }

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }
}