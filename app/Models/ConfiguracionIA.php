<?php
// filepath: app/Models/ConfiguracionIa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionIa extends Model
{
    use HasFactory;

    protected $table = 'configuracion_ia';
    protected $primaryKey = 'id_config';
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'modelo_activo',
        'url_endpoint',
        'max_tokens',
        'temperatura',
        'chunk_size',
        'chunk_overlap',
        'top_k',
        'similarity_threshold',
        'parametros_adicionales',
        'i_active',
    ];

    protected $casts = [
        'max_tokens' => 'integer',
        'temperatura' => 'decimal:2',
        'chunk_size' => 'integer',
        'chunk_overlap' => 'integer',
        'top_k' => 'integer',
        'similarity_threshold' => 'decimal:2',
        'parametros_adicionales' => 'array',
        'i_active' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActiva($query)
    {
        return $query->where('i_active', true)->latest();
    }

    public function scopePorModelo($query, $modelo)
    {
        return $query->where('modelo_activo', $modelo);
    }

    // ========================================
    // MÉTODOS ESTÁTICOS
    // ========================================

    public static function obtenerActiva(): ?self
    {
        return self::activa()->first();
    }

    public static function crearConfiguracionPorDefecto(): self
    {
        // Desactivar configuraciones anteriores
        self::where('i_active', true)->update(['i_active' => false]);

        return self::create([
            'modelo_activo' => 'llama3',
            'url_endpoint' => 'http://localhost:11434',
            'max_tokens' => 2048,
            'temperatura' => 0.70,
            'chunk_size' => 500,
            'chunk_overlap' => 50,
            'top_k' => 5,
            'similarity_threshold' => 0.70,
            'i_active' => true,
        ]);
    }

    // ========================================
    // ACCESORIOS
    // ========================================

    public function getEsModeloLocalAttribute(): bool
    {
        return in_array($this->modelo_activo, ['llama3', 'mistral', 'gemma']);
    }

    public function getEsModeloNubeAttribute(): bool
    {
        return in_array($this->modelo_activo, ['gpt-4', 'claude-3', 'gemini']);
    }

    public function getUrlCompletaAttribute(): string
    {
        if (str_ends_with($this->url_endpoint, '/')) {
            return $this->url_endpoint . 'api/generate';
        }
        
        return $this->url_endpoint . '/api/generate';
    }

    // ========================================
    // MÉTODOS DE CONFIGURACIÓN
    // ========================================

    public function activar(): bool
    {
        // Desactivar otras configuraciones
        self::where('id_config', '!=', $this->id_config)
            ->update(['i_active' => false]);

        $this->i_active = true;
        return $this->save();
    }

    public function desactivar(): bool
    {
        $this->i_active = false;
        return $this->save();
    }

    public function actualizarParametros(array $parametros): bool
    {
        $camposPermitidos = [
            'modelo_activo',
            'url_endpoint',
            'max_tokens',
            'temperatura',
            'chunk_size',
            'chunk_overlap',
            'top_k',
            'similarity_threshold',
            'parametros_adicionales',
        ];

        foreach ($parametros as $campo => $valor) {
            if (in_array($campo, $camposPermitidos)) {
                $this->$campo = $valor;
            }
        }

        return $this->save();
    }

    public function validarConexion(): bool
    {
        try {
            if ($this->es_modelo_local) {
                // Validar Ollama
                $response = @file_get_contents($this->url_endpoint . '/api/tags');
                return $response !== false;
            }
            
            // Validar modelos en nube (implementar según API)
            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }

    public function obtenerModelosDisponibles(): array
    {
        if (!$this->es_modelo_local) {
            return [];
        }

        try {
            $response = @file_get_contents($this->url_endpoint . '/api/tags');
            
            if ($response === false) {
                return [];
            }

            $data = json_decode($response, true);
            
            return array_map(function($modelo) {
                return [
                    'name' => $modelo['name'] ?? '',
                    'size' => $modelo['size'] ?? 0,
                    'modified_at' => $modelo['modified_at'] ?? null,
                ];
            }, $data['models'] ?? []);
            
        } catch (\Exception $e) {
            return [];
        }
    }

    // ========================================
    // MÉTODOS PARA GENERACIÓN
    // ========================================

    public function obtenerParametrosGeneracion(): array
    {
        return [
            'model' => $this->modelo_activo,
            'max_tokens' => $this->max_tokens,
            'temperature' => (float) $this->temperatura,
            'top_k' => $this->top_k,
        ];
    }

    public function obtenerParametrosRAG(): array
    {
        return [
            'chunk_size' => $this->chunk_size,
            'chunk_overlap' => $this->chunk_overlap,
            'top_k' => $this->top_k,
            'similarity_threshold' => (float) $this->similarity_threshold,
        ];
    }

    // ========================================
    // VALIDACIONES
    // ========================================

    public function validarParametros(): array
    {
        $errores = [];

        if ($this->temperatura < 0 || $this->temperatura > 1) {
            $errores[] = 'La temperatura debe estar entre 0 y 1';
        }

        if ($this->max_tokens < 100 || $this->max_tokens > 32000) {
            $errores[] = 'max_tokens debe estar entre 100 y 32000';
        }

        if ($this->chunk_size < 100 || $this->chunk_size > 2000) {
            $errores[] = 'chunk_size debe estar entre 100 y 2000';
        }

        if ($this->chunk_overlap >= $this->chunk_size) {
            $errores[] = 'chunk_overlap debe ser menor que chunk_size';
        }

        if ($this->similarity_threshold < 0 || $this->similarity_threshold > 1) {
            $errores[] = 'similarity_threshold debe estar entre 0 y 1';
        }

        return $errores;
    }

    public function esConfiguracionValida(): bool
    {
        return empty($this->validarParametros());
    }
}