<?php
// filepath: app/Models/ConsultaAsistente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultaAsistente extends Model
{
    use HasFactory;

    protected $table = 'consulta_asistente';
    protected $primaryKey = 'id_consulta';
    
    const CREATED_AT = 'fecha_consulta';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_usuario',
        'id_resolucion',
        'tipo_consulta',
        'pregunta',
        'respuesta',
        'contexto_usado',
        'chunks_usados',
        'tiempo_respuesta_ms',
        'score_relevancia',
        'modelo_ia',
        'metadata_consulta',
        'valoracion',
    ];

    protected $casts = [
        'chunks_usados' => 'array',
        'metadata_consulta' => 'array',
        'tiempo_respuesta_ms' => 'integer',
        'score_relevancia' => 'decimal:4',
        'valoracion' => 'integer',
        'fecha_consulta' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopePorResolucion($query, $idResolucion)
    {
        return $query->where('id_resolucion', $idResolucion);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_consulta', $tipo);
    }

    public function scopeGeneral($query)
    {
        return $query->where('tipo_consulta', 'general');
    }

    public function scopeBusqueda($query)
    {
        return $query->where('tipo_consulta', 'busqueda_resolucion');
    }

    public function scopeExplicacion($query)
    {
        return $query->where('tipo_consulta', 'explicacion');
    }

    public function scopeResumen($query)
    {
        return $query->where('tipo_consulta', 'resumen');
    }

    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('fecha_consulta', '>=', now()->subDays($dias));
    }

    public function scopeConVotosPositivos($query)
    {
        return $query->where('valoracion', '>=', 4);
    }

    public function scopeConVotosNegativos($query)
    {
        return $query->where('valoracion', '<=', 2);
    }

    public function scopeRapidas($query, $milisegundos = 1000)
    {
        return $query->where('tiempo_respuesta_ms', '<=', $milisegundos);
    }

    public function scopeLentas($query, $milisegundos = 5000)
    {
        return $query->where('tiempo_respuesta_ms', '>=', $milisegundos);
    }

    // ========================================
    // ACCESORIOS
    // ========================================

    public function getTiempoRespuestaSegundosAttribute(): float
    {
        return round($this->tiempo_respuesta_ms / 1000, 2);
    }

    public function getEsRespuestaRelevante(): bool
    {
        return $this->score_relevancia && $this->score_relevancia >= 0.7;
    }

    public function getTieneVotacionAttribute(): bool
    {
        return !is_null($this->valoracion);
    }

    public function getNumChunksUsadosAttribute(): int
    {
        return is_array($this->chunks_usados) ? count($this->chunks_usados) : 0;
    }

    public function getCalificacionTextoAttribute(): string
    {
        if (is_null($this->valoracion)) {
            return 'Sin valorar';
        }

        return match($this->valoracion) {
            5 => 'Excelente',
            4 => 'Muy buena',
            3 => 'Buena',
            2 => 'Regular',
            1 => 'Mala',
            default => 'Sin valorar'
        };
    }

    public function getColorVotacionAttribute(): string
    {
        if (is_null($this->valoracion)) {
            return 'gray';
        }

        return match(true) {
            $this->valoracion >= 4 => 'green',
            $this->valoracion === 3 => 'yellow',
            default => 'red'
        };
    }

    // ========================================
    // MÉTODOS DE NEGOCIO
    // ========================================

    public function valorar(int $puntuacion): bool
    {
        if ($puntuacion < 1 || $puntuacion > 5) {
            return false;
        }

        $this->valoracion = $puntuacion;
        return $this->save();
    }

    public static function crearConsulta(
        int $idUsuario,
        string $pregunta,
        string $respuesta,
        string $tipoConsulta = 'general',
        ?int $idResolucion = null,
        array $opciones = []
    ): self {
        return self::create([
            'id_usuario' => $idUsuario,
            'id_resolucion' => $idResolucion,
            'tipo_consulta' => $tipoConsulta,
            'pregunta' => $pregunta,
            'respuesta' => $respuesta,
            'contexto_usado' => $opciones['contexto'] ?? null,
            'chunks_usados' => $opciones['chunks'] ?? null,
            'tiempo_respuesta_ms' => $opciones['tiempo'] ?? null,
            'score_relevancia' => $opciones['score'] ?? null,
            'modelo_ia' => $opciones['modelo'] ?? 'default',
            'metadata_consulta' => $opciones['metadata'] ?? null,
        ]);
    }

    public function obtenerChunksDetalles(): array
    {
        if (empty($this->chunks_usados)) {
            return [];
        }

        return DocumentoChunk::whereIn('id_chunk', $this->chunks_usados)
            ->with('documentoTexto.resolucion')
            ->get()
            ->map(function($chunk) {
                return [
                    'id_chunk' => $chunk->id_chunk,
                    'contenido' => $chunk->contenido_chunk,
                    'num_resolucion' => $chunk->documentoTexto->resolucion->num_resolucion ?? null,
                    'chunk_index' => $chunk->chunk_index,
                ];
            })
            ->toArray();
    }

    // ========================================
    // ESTADÍSTICAS
    // ========================================

    public static function obtenerEstadisticas(int $dias = 30): array
    {
        $consultas = self::where('fecha_consulta', '>=', now()->subDays($dias))->get();

        return [
            'total_consultas' => $consultas->count(),
            'tiempo_promedio_ms' => $consultas->avg('tiempo_respuesta_ms'),
            'score_promedio' => $consultas->avg('score_relevancia'),
            'valoracion_promedio' => $consultas->whereNotNull('valoracion')->avg('valoracion'),
            'por_tipo' => $consultas->groupBy('tipo_consulta')
                ->map(fn($grupo) => $grupo->count()),
            'por_modelo' => $consultas->groupBy('modelo_ia')
                ->map(fn($grupo) => $grupo->count()),
            'consultas_valoradas' => $consultas->whereNotNull('valoracion')->count(),
            'consultas_positivas' => $consultas->where('valoracion', '>=', 4)->count(),
            'consultas_negativas' => $consultas->where('valoracion', '<=', 2)->count(),
        ];
    }

    public static function obtenerConsultasFrecuentes(int $limit = 10): array
    {
        return self::selectRaw('pregunta, COUNT(*) as total')
            ->groupBy('pregunta')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}