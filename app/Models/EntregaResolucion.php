<?php
// filepath: app/Models/EntregaResolucion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaResolucion extends Model
{
    protected $table = 'entrega_resolucion';
    protected $primaryKey = 'id_entrega_resolucion';
    public $timestamps = false;

    protected $fillable = [
        'id_resolucion',
        'id_persona_entrega',
        'correo_entrega',
        'archivo_firmado',
        'id_usuario_firma',
        'cuenta_creada',
        'fecha_entrega',
    ];

    protected $casts = [
        'cuenta_creada' => 'boolean',
        'fecha_entrega' => 'datetime',
    ];

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion', 'id_resolucion');
    }

    public function personaEntrega()
    {
        return $this->belongsTo(Persona::class, 'id_persona_entrega', 'id_persona');
    }

    public function usuarioFirma()
    {
        return $this->belongsTo(User::class, 'id_usuario_firma');
    }
}
