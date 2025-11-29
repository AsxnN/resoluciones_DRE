<?php
// filepath: app/Models/DocumentoTextoOcr.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentoTextoOcr extends Model
{
    use HasFactory;

    protected $table = 'documento_texto_ocr';
    protected $primaryKey = 'id_documento_texto';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'id_resolucion',
        'texto_completo',
        'texto_procesado',
        'metadatos_ocr',
        'estado_procesamiento',
        'fecha_procesamiento',
    ];

    protected $casts = [
        'metadatos_ocr' => 'array',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'fecha_procesamiento' => 'datetime',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'id_resolucion');
    }

    public function chunks()
    {
        return $this->hasMany(DocumentoChunk::class, 'id_documento_texto');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePendientes($query)
    {
        return $query->where('estado_procesamiento', 'pendiente');
    }

    public function scopeProcesados($query)
    {
        return $query->where('estado_procesamiento', 'procesado');
    }

    public function scopeConError($query)
    {
        return $query->where('estado_procesamiento', 'error');
    }

    public function scopeBuscarEnTexto($query, $busqueda)
    {
        return $query->where(function($q) use ($busqueda) {
            $q->whereRaw('MATCH(texto_completo, texto_procesado) AGAINST(? IN BOOLEAN MODE)', [$busqueda])
              ->orWhere('texto_completo', 'like', "%{$busqueda}%")
              ->orWhere('texto_procesado', 'like', "%{$busqueda}%");
        });
    }

    // ========================================
    // ACCESORIOS
    // ========================================

    public function getEstaProcesadoAttribute(): bool
    {
        return $this->estado_procesamiento === 'procesado';
    }

    public function getTieneErrorAttribute(): bool
    {
        return $this->estado_procesamiento === 'error';
    }

    public function getNumeroChunksAttribute(): int
    {
        return $this->chunks()->count();
    }

    public function getNumPaginasAttribute(): ?int
    {
        return $this->metadatos_ocr['num_paginas'] ?? null;
    }

    public function getCalidadOcrAttribute(): ?float
    {
        return $this->metadatos_ocr['calidad_promedio'] ?? null;
    }

    // ========================================
    // MÉTODOS DE NEGOCIO
    // ========================================

    public function marcarComoProcesado(): bool
    {
        $this->estado_procesamiento = 'procesado';
        $this->fecha_procesamiento = now();
        
        return $this->save();
    }

    public function marcarComoError(string $mensajeError): bool
    {
        $this->estado_procesamiento = 'error';
        $this->fecha_procesamiento = now();
        
        $metadatos = $this->metadatos_ocr ?? [];
        $metadatos['error'] = $mensajeError;
        $metadatos['fecha_error'] = now()->toIso8601String();
        $this->metadatos_ocr = $metadatos;
        
        return $this->save();
    }

    public function procesarTexto(string $textoOriginal, array $metadatos = []): bool
    {
        // Guardar texto original
        $this->texto_completo = $textoOriginal;

        // Procesar texto (limpiar, normalizar)
        $this->texto_procesado = $this->limpiarTexto($textoOriginal);

        // Guardar metadatos
        $this->metadatos_ocr = array_merge($this->metadatos_ocr ?? [], $metadatos);

        return $this->save();
    }

    public function generarChunks(int $chunkSize = 500, int $chunkOverlap = 50): int
    {
        if (empty($this->texto_procesado)) {
            return 0;
        }

        // Eliminar chunks existentes
        $this->chunks()->delete();

        $texto = $this->texto_procesado;
        $chunks = [];
        $inicio = 0;
        $indice = 0;

        while ($inicio < strlen($texto)) {
            $chunk = substr($texto, $inicio, $chunkSize);
            
            // Intentar cortar en punto final o salto de línea
            if ($inicio + $chunkSize < strlen($texto)) {
                $ultimoPunto = strrpos($chunk, '.');
                $ultimaSalto = strrpos($chunk, "\n");
                $corte = max($ultimoPunto, $ultimaSalto);
                
                if ($corte !== false && $corte > $chunkSize * 0.5) {
                    $chunk = substr($chunk, 0, $corte + 1);
                }
            }

            $chunks[] = [
                'id_documento_texto' => $this->id_documento_texto,
                'chunk_index' => $indice,
                'contenido_chunk' => trim($chunk),
                'metadata_chunk' => json_encode([
                    'posicion_inicio' => $inicio,
                    'longitud' => strlen($chunk),
                    'num_palabras' => str_word_count($chunk),
                ]),
                'num_tokens' => $this->estimarTokens($chunk),
                'fecha_creacion' => now(),
            ];

            $inicio += $chunkSize - $chunkOverlap;
            $indice++;
        }

        if (!empty($chunks)) {
            DocumentoChunk::insert($chunks);
        }

        return count($chunks);
    }

    public function buscarChunksRelevantes(string $consulta, int $topK = 5): array
    {
        // Búsqueda simple por similitud de texto
        // En producción usar embeddings y búsqueda vectorial
        
        $consultaNormalizada = strtolower($this->limpiarTexto($consulta));
        $palabrasClave = explode(' ', $consultaNormalizada);
        
        return $this->chunks()
            ->get()
            ->map(function($chunk) use ($palabrasClave) {
                $contenidoNormalizado = strtolower($chunk->contenido_chunk);
                
                $score = 0;
                foreach ($palabrasClave as $palabra) {
                    if (strlen($palabra) > 3) {
                        $score += substr_count($contenidoNormalizado, $palabra);
                    }
                }
                
                $chunk->score_relevancia = $score;
                return $chunk;
            })
            ->sortByDesc('score_relevancia')
            ->take($topK)
            ->values()
            ->toArray();
    }

    // ========================================
    // MÉTODOS AUXILIARES PRIVADOS
    // ========================================

    private function limpiarTexto(string $texto): string
    {
        // Normalizar espacios
        $texto = preg_replace('/\s+/', ' ', $texto);
        
        // Eliminar caracteres especiales extraños
        $texto = preg_replace('/[^\p{L}\p{N}\s\.\,\;\:\-\(\)]/u', '', $texto);
        
        // Normalizar saltos de línea
        $texto = str_replace(["\r\n", "\r"], "\n", $texto);
        
        return trim($texto);
    }

    private function estimarTokens(string $texto): int
    {
        // Estimación aproximada: 1 token ≈ 4 caracteres en español
        return (int) ceil(strlen($texto) / 4);
    }

    public function extraerMetadatosDocumento(): array
    {
        $metadatos = [
            'num_caracteres' => strlen($this->texto_completo),
            'num_palabras' => str_word_count($this->texto_completo),
            'num_lineas' => substr_count($this->texto_completo, "\n"),
            'fecha_extraccion' => now()->toIso8601String(),
        ];

        // Detectar fechas en el texto
        preg_match_all('/\d{1,2}[-\/]\d{1,2}[-\/]\d{2,4}/', $this->texto_completo, $fechas);
        if (!empty($fechas[0])) {
            $metadatos['fechas_encontradas'] = array_unique($fechas[0]);
        }

        // Detectar números de documento
        preg_match_all('/\b\d{8}\b/', $this->texto_completo, $documentos);
        if (!empty($documentos[0])) {
            $metadatos['posibles_dni'] = array_unique($documentos[0]);
        }

        return $metadatos;
    }
}