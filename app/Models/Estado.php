<?php
// filepath: app/Models/Estado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estado';
    protected $primaryKey = 'id_estado';
    
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado',
        'descripcion',
    ];

    // ========================================
    // RELACIONES
    // ========================================

    public function resoluciones()
    {
        return $this->hasMany(Resolucion::class, 'id_estado');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre_estado', $nombre);
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    public static function obtenerIdPorNombre(string $nombre): ?int
    {
        return self::where('nombre_estado', $nombre)->value('id_estado');
    }

    public function esBorrador(): bool
    {
        return $this->nombre_estado === 'Borrador';
    }

    public function esFirmada(): bool
    {
        return $this->nombre_estado === 'Firmada';
    }

    public function esPublicada(): bool
    {
        return $this->nombre_estado === 'Publicada';
    }

    public function esAnulada(): bool
    {
        return $this->nombre_estado === 'Anulada';
    }
}