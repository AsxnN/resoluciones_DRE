<?php
// filepath: app/Models/Area.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'area';
    protected $primaryKey = 'id_area';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_area',
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
        return $this->hasMany(Colaborador::class, 'id_area');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }
}