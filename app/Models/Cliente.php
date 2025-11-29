<?php
// filepath: app/Models/Cliente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_persona',
        'tipo_cliente',
        'i_active',
    ];

    protected $casts = [
        'i_active' => 'boolean',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function quejas()
    {
        return $this->hasMany(Queja::class, 'id_cliente');
    }

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }
}