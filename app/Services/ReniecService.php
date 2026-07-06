<?php
// filepath: app/Services/ReniecService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReniecService
{
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = config('services.reniec.url');
        $this->token = config('services.reniec.token');
    }

    /**
     * Consultar DNI en RENIEC (PeruDevs API)
     */
    public function consultarDni(string $dni): ?array
    {
        if (strlen($dni) !== 8 || !is_numeric($dni)) {
            return [
                'success' => false,
                'message' => 'DNI debe tener 8 dígitos numéricos'
            ];
        }

        try {
            $response = Http::timeout(15)
                ->get($this->apiUrl, [
                    'document' => $dni,
                    'key' => $this->token
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Verificar si la API devolvió datos válidos según PeruDevs
                if (isset($data['estado']) && $data['estado'] === true && isset($data['resultado'])) {
                    $resultado = $data['resultado'];
                    
                    return [
                        'success' => true,
                        'nombres' => $resultado['nombres'] ?? '',
                        'apellido_paterno' => $resultado['apellido_paterno'] ?? '',
                        'apellido_materno' => $resultado['apellido_materno'] ?? '',
                        'nombre_completo' => $resultado['nombre_completo'] ?? '',
                        'genero' => $resultado['genero'] ?? '',
                        'fecha_nacimiento' => $resultado['fecha_nacimiento'] ?? '',
                        'codigo_verificacion' => $resultado['codigo_verificacion'] ?? '',
                        'tipo_documento' => 'DNI',
                        'num_documento' => $dni,
                    ];
                }

                // Si el estado es false (no encontrado)
                return [
                    'success' => false,
                    'message' => $data['mensaje'] ?? 'No se encontraron datos para este DNI en RENIEC'
                ];
            }

            Log::warning('PeruDevs API error', [
                'dni' => $dni,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Error al consultar PeruDevs (HTTP ' . $response->status() . ')'
            ];

        } catch (\Exception $e) {
            Log::error('PeruDevs API exception', [
                'dni' => $dni,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error de conexión con el servicio: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validar formato de DNI
     */
    public function validarDni(string $dni): bool
    {
        return strlen($dni) === 8 && is_numeric($dni);
    }
}