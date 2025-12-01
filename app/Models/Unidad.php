<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidad';
    protected $primaryKey = 'id_unidad';
    public $timestamps = false;

    protected $fillable = [
        'nombre_unidades',  // ← Cambiado de nombre_unidad a nombre_unidades
        'id_usuario',
        'i_active'
    ];

    protected $casts = [
        'i_active' => 'boolean',
        'fecha_actualizacion' => 'datetime',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_unidades', 'id_unidad');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('i_active', true);
    }

    public function scopeBuscar($query, $search)
    {
        return $query->where('nombre_unidades', 'like', "%{$search}%");
    }
}