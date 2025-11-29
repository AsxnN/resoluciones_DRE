<?php
// filepath: app/Models/Colaborador.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;

    protected $table = 'colaborador';
    protected $primaryKey = 'id_colab_dis';
    public $incrementing = false;
    protected $keyType = 'string';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_colab_dis',
        'id_persona',
        'id_cargos',
        'id_unidades',
        'id_direcciones',
        'id_dependencia',
        'id_area',
        'id_especialidad',
        'id_tipo_personal',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargos', 'id_cargos');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_unidades', 'id_unidades');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'id_direcciones', 'id_direcciones');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'id_dependencia', 'id_dependencias');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'id_especialidad', 'id_especialidad');
    }

    public function tipoPersonal()
    {
        return $this->belongsTo(TipoPersonal::class, 'id_tipo_personal', 'id_tipo_personal');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopeConPersona($query)
    {
        return $query->with('persona');
    }

    public function scopeConRelaciones($query)
    {
        return $query->with([
            'persona',
            'cargo',
            'unidad',
            'direccion',
            'dependencia',
            'area',
            'especialidad',
            'tipoPersonal'
        ]);
    }
}