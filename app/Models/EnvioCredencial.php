<?php
// filepath: app/Models/EnvioCredencial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvioCredencial extends Model
{
    use HasFactory;

    protected $table = 'envios_credenciales';
    protected $primaryKey = 'id_envio_credencial';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_persona_resolucion_datos',
        'correo_destino',
        'correo_sistema_generado',
        'password_generado_hash',
        'id_usuario_envia',
        'estado_envio',
        'observaciones',
        'ip_address',
        'fecha_envio',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // Relación con PersonaResolucionDatos
    public function personaResolucionDatos()
    {
        return $this->belongsTo(PersonaResolucionDatos::class, 'id_persona_resolucion_datos', 'id_persona_resolucion_datos');
    }

    // Relación con el usuario que envió las credenciales
    public function usuarioEnvia()
    {
        return $this->belongsTo(User::class, 'id_usuario_envia', 'id');
    }

    // Accessor para saber si el envío fue exitoso
    public function getExitosoAttribute()
    {
        return $this->estado_envio === 'enviado';
    }

    // Accessor para el nombre completo de la persona
    public function getNombreCompletoAttribute()
    {
        $persona = $this->personaResolucionDatos;
        if (!$persona) return 'N/A';
        
        return trim("{$persona->nombres} {$persona->apellido_paterno} {$persona->apellido_materno}");
    }
}