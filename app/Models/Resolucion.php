<?php
// filepath: app/Models/Resolucion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Resolucion extends Model
{
    use HasFactory;

    protected $table = 'resolucion';
    protected $primaryKey = 'id_resolucion';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_estado',
        'id_tipo_resolucion',
        'num_resolucion',
        'fecha_resolucion',
        'visto_resolucion',
        'asunto_resolucion',
        'archivo_resolucion',
        'archivo_firmado',
        'fecha_firma',
        'id_usuario_firma',
        'enviada_resolucion',
        'fecha_envio',
        'i_active',
        'id_usuario',
    ];

    protected $casts = [
        'fecha_resolucion' => 'date',
        'fecha_firma' => 'datetime',
        'fecha_envio' => 'datetime',
        'enviada_resolucion' => 'boolean',
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    public function tipoResolucion()
    {
        return $this->belongsTo(TipoResolucion::class, 'id_tipo_resolucion', 'id_tipo_resolucion');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function usuarioFirmante()
    {
        return $this->belongsTo(User::class, 'id_usuario_firma');
    }

    // Personas relacionadas (involucrados, notificados, firmantes)
    public function personas()
    {
        return $this->belongsToMany(
            Persona::class,
            'persona_resolucion',
            'id_resolucion',
            'id_persona'
        )->withPivot('tipo_relacion', 'i_notificado', 'fecha_notificacion', 'i_active')
          ->withTimestamps();
    }

    public function personasInvolucradas()
    {
        return $this->belongsToMany(
            Persona::class,
            'persona_resolucion',
            'id_resolucion',
            'id_persona',
            'id_resolucion',
            'id_persona'
        )->withPivot('tipo_relacion')->withTimestamps();
    }

    public function personasNotificadas()
    {
        return $this->personas()->wherePivot('tipo_relacion', 'notificado');
    }

    public function personasFirmantes()
    {
        return $this->personas()->wherePivot('tipo_relacion', 'firmante');
    }

    // Cola de firmas
    public function colaFirmas()
    {
        return $this->hasMany(ColaFirma::class, 'id_resolucion');
    }

    public function firmasPendientes()
    {
        return $this->colaFirmas()
                    ->whereHas('estadoFirma', function($query) {
                        $query->where('nombre_estado', 'Pendiente');
                    });
    }

    public function firmasCompletadas()
    {
        return $this->colaFirmas()
                    ->whereHas('estadoFirma', function($query) {
                        $query->where('nombre_estado', 'Firmado');
                    });
    }

    // Historial de firmas
    public function historialFirmas()
    {
        return $this->hasMany(HistorialFirma::class, 'id_resolucion');
    }

    // Notificaciones
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_resolucion');
    }

    // Quejas
    public function quejas()
    {
        return $this->hasMany(Queja::class, 'id_resolucion');
    }

    // Documento OCR (texto extraído)
    public function documentoTexto()
    {
        return $this->hasOne(DocumentoTextoOcr::class, 'id_resolucion');
    }

    // Consultas IA relacionadas
    public function consultasAsistente()
    {
        return $this->hasMany(ConsultaAsistente::class, 'id_resolucion');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActivos($query)
    {
        return $query->where('i_active', true);
    }

    public function scopePorEstado($query, $idEstado)
    {
        return $query->where('id_estado', $idEstado);
    }

    public function scopeBorrador($query)
    {
        return $query->whereHas('estado', function($q) {
            $q->where('nombre_estado', 'Borrador');
        });
    }

    public function scopeFirmadas($query)
    {
        return $query->whereHas('estado', function($q) {
            $q->where('nombre_estado', 'Firmada');
        });
    }

    public function scopePublicadas($query)
    {
        return $query->whereHas('estado', function($q) {
            $q->where('nombre_estado', 'Publicada');
        });
    }

    public function scopePendientesFirma($query)
    {
        return $query->whereHas('estado', function($q) {
            $q->where('nombre_estado', 'Pendiente de Firma');
        });
    }

    public function scopePorTipo($query, $idTipo)
    {
        return $query->where('id_tipo_resolucion', $idTipo);
    }

    public function scopePorAnio($query, $anio)
    {
        return $query->whereYear('fecha_resolucion', $anio);
    }

    public function scopeBuscar($query, $busqueda)
    {
        return $query->where(function($q) use ($busqueda) {
            $q->where('num_resolucion', 'like', "%{$busqueda}%")
              ->orWhere('asunto_resolucion', 'like', "%{$busqueda}%")
              ->orWhere('visto_resolucion', 'like', "%{$busqueda}%");
        });
    }

    public function scopeConRelaciones($query)
    {
        return $query->with([
            'estado',
            'tipoResolucion',
            'usuarioCreador.persona',
            'usuarioFirmante.persona'
        ]);
    }

    // ========================================
    // ACCESORIOS
    // ========================================

    public function getUrlArchivoAttribute(): ?string
    {
        return $this->archivo_resolucion 
            ? Storage::url($this->archivo_resolucion) 
            : null;
    }

    public function getUrlArchivoFirmadoAttribute(): ?string
    {
        return $this->archivo_firmado 
            ? Storage::url($this->archivo_firmado) 
            : null;
    }

    public function getTieneArchivoAttribute(): bool
    {
        return !empty($this->archivo_resolucion) && Storage::exists($this->archivo_resolucion);
    }

    public function getEstaFirmadaAttribute(): bool
    {
        return !empty($this->archivo_firmado) && !empty($this->fecha_firma);
    }

    public function getEsBorradorAttribute(): bool
    {
        return $this->estado?->nombre_estado === 'Borrador';
    }

    public function getPuedeEditarseAttribute(): bool
    {
        return in_array($this->estado?->nombre_estado, ['Borrador', 'Observada']);
    }

    public function getPuedeFirmarseAttribute(): bool
    {
        return $this->estado?->nombre_estado === 'Pendiente de Firma';
    }

    public function getTieneFirmasPendientesAttribute(): bool
    {
        return $this->firmasPendientes()->exists();
    }

    // ========================================
    // MÉTODOS DE NEGOCIO
    // ========================================

    public function cambiarEstado(int $idEstado, ?int $idUsuario = null): bool
    {
        $estadoAnterior = $this->id_estado;
        
        $this->id_estado = $idEstado;
        $resultado = $this->save();

        if ($resultado) {
            // Registrar auditoría
            Auditoria::create([
                'tabla_afectada' => 'resolucion',
                'id_registro' => $this->id_resolucion,
                'accion' => 'cambio_estado',
                'datos_anteriores' => ['id_estado' => $estadoAnterior],
                'datos_nuevos' => ['id_estado' => $idEstado],
                'id_usuario' => $idUsuario ?? auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'descripcion' => "Estado cambiado de {$estadoAnterior} a {$idEstado}",
            ]);
        }

        return $resultado;
    }

    public function marcarComoEnviada(?int $idUsuario = null): bool
    {
        $this->enviada_resolucion = true;
        $this->fecha_envio = now();
        $resultado = $this->save();

        if ($resultado) {
            Auditoria::create([
                'tabla_afectada' => 'resolucion',
                'id_registro' => $this->id_resolucion,
                'accion' => 'notificar',
                'id_usuario' => $idUsuario ?? auth()->id(),
                'descripcion' => "Resolución {$this->num_resolucion} enviada",
            ]);
        }

        return $resultado;
    }

    public function registrarFirma(int $idUsuarioFirma, string $rutaArchivoFirmado): bool
    {
        $this->archivo_firmado = $rutaArchivoFirmado;
        $this->fecha_firma = now();
        $this->id_usuario_firma = $idUsuarioFirma;
        
        // Cambiar estado a "Firmada"
        $estadoFirmada = Estado::where('nombre_estado', 'Firmada')->first();
        if ($estadoFirmada) {
            $this->id_estado = $estadoFirmada->id_estado;
        }

        $resultado = $this->save();

        if ($resultado) {
            Auditoria::create([
                'tabla_afectada' => 'resolucion',
                'id_registro' => $this->id_resolucion,
                'accion' => 'firmar',
                'datos_nuevos' => [
                    'archivo_firmado' => $rutaArchivoFirmado,
                    'id_usuario_firma' => $idUsuarioFirma,
                    'fecha_firma' => now(),
                ],
                'id_usuario' => $idUsuarioFirma,
                'descripcion' => "Resolución {$this->num_resolucion} firmada digitalmente",
            ]);
        }

        return $resultado;
    }

    public function solicitarFirma(int $idUsuarioFirmante, int $idUsuarioSolicita, string $prioridad = 'media'): ?ColaFirma
    {
        $estadoPendiente = EstadoFirma::where('nombre_estado', 'Pendiente')->first();
        
        if (!$estadoPendiente) {
            return null;
        }

        return ColaFirma::create([
            'id_resolucion' => $this->id_resolucion,
            'id_usuario_solicita' => $idUsuarioSolicita,
            'id_usuario_firmante' => $idUsuarioFirmante,
            'id_estado_firma' => $estadoPendiente->id_estado_firma,
            'prioridad' => $prioridad,
            'fecha_limite' => now()->addDays(3), // 3 días para firmar
        ]);
    }

    public function personasRelacionadasDatos()
    {
        return $this->hasMany(PersonaResolucionDatos::class, 'id_resolucion', 'id_resolucion')
                    ->orderBy('fecha_creacion', 'asc');
    }

    public function getPersonasAttribute()
    {
        return $this->personasRelacionadasDatos;
    }

    // Nueva relación para persona_resolucion_datos
    public function personasRelacionadas()
    {
        return $this->hasMany(PersonaResolucionDatos::class, 'id_resolucion', 'id_resolucion');
    }
}