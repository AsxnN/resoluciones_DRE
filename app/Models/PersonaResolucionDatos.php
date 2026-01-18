<?php
// filepath: app/Models/PersonaResolucionDatos.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaResolucionDatos extends Model
{
    use HasFactory;

    protected $table = 'persona_resolucion_datos';
    protected $primaryKey = 'id_persona_resolucion_datos';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_resolucion',
        'id_user',
        'tipo_documento',
        'num_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'tipo_relacion',
        'descripcion_relacion',
        'obtenido_reniec',
        'es_interna',
        'asignado_a_cliente',
        'fecha_asignacion',
    ];

    protected $casts = [
        'obtenido_reniec' => 'boolean',
        'es_interna' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'asignado_a_cliente' => 'boolean',
        'fecha_asignacion' => 'datetime',
    ];

    // Relación con Resolución
    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion', 'id_resolucion');
    }

    // Relación con User (para personas internas)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // ← NUEVO: Relación con envíos de credenciales
    public function enviosCredenciales()
    {
        return $this->hasMany(EnvioCredencial::class, 'id_persona_resolucion_datos', 'id_persona_resolucion_datos')
                    ->orderBy('fecha_envio', 'desc');
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    // ← NUEVO: Verificar si tiene envíos de credenciales
    public function tieneEnviosCredenciales()
    {
        return $this->enviosCredenciales()->exists();
    }

    // ← NUEVO: Obtener último envío de credenciales
    public function ultimoEnvioCredencial()
    {
        return $this->enviosCredenciales()->latest('fecha_envio')->first();
    }
}