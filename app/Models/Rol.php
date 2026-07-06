<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles_organizacionales';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_rol',
        'descripcion',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // Relaciones

    // id_rol vive en la tabla `users` (rol organizacional del usuario), no en `colaborador`.
    public function usuariosConEsteRol()
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }

    // Usuario que creó/registró este rol (id_usuario es un campo distinto de id_rol)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
  