<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Colaborador extends Model
{
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

    // Relaciones
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'id_cargos', 'id_cargos');
    }

    public function direccion(): BelongsTo
    {
        return $this->belongsTo(Direccion::class, 'id_direcciones', 'id_direcciones');
    }

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'id_dependencia', 'id_dependencias');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class, 'id_especialidad', 'id_especialidad');
    }

    public function tipoPersonal(): BelongsTo
    {
        return $this->belongsTo(TipoPersonal::class, 'id_tipo_personal', 'id_tipo_personal');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}