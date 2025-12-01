<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direccion';
    protected $primaryKey = 'id_direcciones';
    public $timestamps = false;

    protected $fillable = [
        'nombre_direcciones',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
    ];

    // Relaciones
    public function personas()
    {
        return $this->hasMany(Persona::class, 'id_direcciones', 'id_direcciones');
    }

    public function areas()
    {
        return $this->hasMany(Area::class, 'id_direcciones', 'id_direcciones');
    }

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_direcciones', 'id_direcciones');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('i_active', true);
    }

    public function scopeBuscar($query, $search)
    {
        return $query->where('nombre_direcciones', 'like', "%{$search}%");
    }
}