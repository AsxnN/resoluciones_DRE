<?php
// filepath: app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles; // ← TRAIT DE SPATIE

    protected $guard_name = 'colaborador'; // ← GUARD PARA SPATIE

    protected $fillable = [
        'id_persona',
        'tipo_acceso',
        'name',
        'email',
        'email_alternativo',
        'password',
        'i_active',
        'ultima_sesion',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'i_active' => 'boolean',
        'ultima_sesion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function colaborador()
    {
        return $this->hasOneThrough(
            Colaborador::class,
            Persona::class,
            'id_persona', // FK en tabla persona
            'id_persona', // FK en tabla colaborador
            'id_persona', // PK en tabla users
            'id_persona'  // PK en tabla persona
        );
    }

    // Agregar esta relación:
    public function cliente()
    {
        return $this->hasOneThrough(
            Cliente::class,
            Persona::class,
            'id_persona', // FK en tabla persona
            'id_persona', // FK en tabla cliente
            'id_persona', // PK en tabla users
            'id_persona'  // PK en tabla persona
        );
    }

    // Permisos metadata (auditoría custom)
    public function permisosMetadata()
    {
        return $this->hasMany(UsuarioPermisoMetadata::class, 'id_usuario');
    }

    public function permisosAsignados()
    {
        return $this->hasMany(UsuarioPermisoMetadata::class, 'id_usuario_asigna');
    }

    // Resoluciones
    public function resoluciones()
    {
        return $this->hasMany(Resolucion::class, 'id_usuario');
    }

    public function resolucionesFirmadas()
    {
        return $this->hasMany(Resolucion::class, 'id_usuario_firma');
    }

    // Firmas
    public function firmasSolicitadas()
    {
        return $this->hasMany(ColaFirma::class, 'id_usuario_solicita');
    }

    public function firmasPendientes()
    {
        return $this->hasMany(ColaFirma::class, 'id_usuario_firmante');
    }

    public function historialFirmas()
    {
        return $this->hasMany(HistorialFirma::class, 'id_usuario');
    }

    // Notificaciones
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }

    public function notificacionesNoLeidas()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario')
                    ->where('i_leido', false)
                    ->orderBy('fecha_notificacion', 'desc');
    }

    // Auditorías
    public function auditorias()
    {
        return $this->hasMany(Auditoria::class, 'id_usuario');
    }

    // Consultas IA
    public function consultasAsistente()
    {
        return $this->hasMany(ConsultaAsistente::class, 'id_usuario');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('tipo_acceso', 'admin');
    }

    public function scopeColaboradores($query)
    {
        return $query->where('tipo_acceso', 'colaborador');
    }

    public function scopeClientes($query)
    {
        return $query->where('tipo_acceso', 'cliente');
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    public function esAdmin(): bool
    {
        return $this->tipo_acceso === 'admin';
    }

    public function esColaborador(): bool
    {
        return $this->tipo_acceso === 'colaborador';
    }

    public function esCliente(): bool
    {
        return $this->tipo_acceso === 'cliente';
    }

    public function tienePermisoEnModulo(string $moduloSlug): bool
    {
        return $this->permissions()
                    ->whereHas('modulo', function($query) use ($moduloSlug) {
                        $query->where('slug', $moduloSlug);
                    })
                    ->exists();
    }

    public function obtenerPermisosActivos()
    {
        return $this->permisosMetadata()
                    ->where('i_active', true)
                    ->with(['permiso.modulo'])
                    ->get();
    }
}