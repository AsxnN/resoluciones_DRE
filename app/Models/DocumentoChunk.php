<?php
// filepath: app/Models/DocumentoChunk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoChunk extends Model
{
    use HasFactory;

    protected $table = 'documento_chunks';
    protected $primaryKey = 'id_chunk';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_documento_texto',
        'chunk_index',
        'contenido_chunk',
        'metadata_chunk',
        'embedding',
        'num_tokens',
    ];

    protected $casts = [
        'metadata_chunk' => 'array',
        'embedding' => 'array',
        'chunk_index' => 'integer',
        'num_tokens' => 'integer',
        'fecha_creacion' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function documentoTexto()
    {
        return $this->belongsTo(DocumentoTextoOcr::class, 'id_documento_texto');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePorDocumento($query, $idDocumento)
    {
        return $query->where('id_documento_texto', $idDocumento);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('chunk_index');
    }

    public function scopeConEmbedding($query)
    {
        return $query->whereNotNull('embedding');
    }

    public function scopeSinEmbedding($query)
    {
        return $query->whereNull('embedding');
    }

    // ========================================
    // ACCESORIOS
    // ========================================

    public function getTieneEmbeddingAttribute(): bool
    {
        return !empty($this->embedding);
    }

    public function getNumPalabrasAttribute(): int
    {
        return str_word_count($this->contenido_chunk);
    }

    public function getPosicionInicioAttribute(): ?int
    {
        return $this->metadata_chunk['posicion_inicio'] ?? null;
    }

    public function getLongitudAttribute(): ?int
    {
        return $this->metadata_chunk['longitud'] ?? null;
    }

    // ========================================
    // MÉTODOS DE BÚSQUEDA VECTORIAL
    // ========================================

    public function generarEmbedding(array $embedding): bool
    {
        $this->embedding = $embedding;
        return $this->save();
    }

    public static function buscarPorSimilitud(array $queryEmbedding, int $topK = 5): array
    {
        // Búsqueda por similitud coseno
        // En producción usar vectores optimizados (pgvector, Milvus, etc.)
        
        return self::whereNotNull('embedding')
            ->get()
            ->map(function($chunk) use ($queryEmbedding) {
                $chunk->similitud = self::similitudCoseno(
                    $queryEmbedding, 
                    $chunk->embedding
                );
                return $chunk;
            })
            ->sortByDesc('similitud')
            ->take($topK)
            ->values()
            ->toArray();
    }

    private static function similitudCoseno(array $vectorA, array $vectorB): float
    {
        if (count($vectorA) !== count($vectorB)) {
            return 0.0;
        }

        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        for ($i = 0; $i < count($vectorA); $i++) {
            $dotProduct += $vectorA[$i] * $vectorB[$i];
            $magnitudeA += $vectorA[$i] ** 2;
            $magnitudeB += $vectorB[$i] ** 2;
        }

        $magnitudeA = sqrt($magnitudeA);
        $magnitudeB = sqrt($magnitudeB);

        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0.0;
        }

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    public function obtenerContexto(int $chunksBefore = 1, int $chunksAfter = 1): string
    {
        $contexto = [];

        // Chunks anteriores
        if ($chunksBefore > 0) {
            $anteriores = self::where('id_documento_texto', $this->id_documento_texto)
                ->where('chunk_index', '<', $this->chunk_index)
                ->orderBy('chunk_index', 'desc')
                ->limit($chunksBefore)
                ->pluck('contenido_chunk')
                ->reverse()
                ->toArray();
            
            $contexto = array_merge($contexto, $anteriores);
        }

        // Chunk actual
        $contexto[] = $this->contenido_chunk;

        // Chunks posteriores
        if ($chunksAfter > 0) {
            $posteriores = self::where('id_documento_texto', $this->id_documento_texto)
                ->where('chunk_index', '>', $this->chunk_index)
                ->orderBy('chunk_index')
                ->limit($chunksAfter)
                ->pluck('contenido_chunk')
                ->toArray();
            
            $contexto = array_merge($contexto, $posteriores);
        }

        return implode("\n\n", $contexto);
    }
}