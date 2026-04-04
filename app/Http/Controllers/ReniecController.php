<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReniecService;
use Illuminate\Support\Facades\Log;

class ReniecController extends Controller
{
    protected $reniecService;

    public function __construct(ReniecService $reniecService)
    {
        $this->reniecService = $reniecService;
    }

    /**
     * Consultar DNI en RENIEC
     */
    public function consultarDni($dni)
    {
        // Validar DNI
        if (!$this->reniecService->validarDni($dni)) {
            return response()->json([
                'success' => false,
                'message' => 'El DNI debe tener 8 dígitos numéricos'
            ], 400);
        }

        try {
            $resultado = $this->reniecService->consultarDni($dni);

            if ($resultado['success']) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'nombres' => $resultado['nombres'] ?? '',
                        'apellidoPaterno' => $resultado['apellido_paterno'] ?? '',
                        'apellidoMaterno' => $resultado['apellido_materno'] ?? '',
                        'nombreCompleto' => $resultado['nombre_completo'] ?? '',
                        'dni' => $dni,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $resultado['message'] ?? 'No se encontraron datos para este DNI'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error en ReniecController', [
                'dni' => $dni,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al consultar RENIEC: ' . $e->getMessage()
            ], 500);
        }
    }
}   