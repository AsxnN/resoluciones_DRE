<?php
// filepath: routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReniecController;

/*
|--------------------------------------------------------------------------
| API Routes (Opcional - para integraciones futuras)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// APIs para chatbot
Route::middleware(['auth:sanctum'])->prefix('chatbot')->group(function () {
    // Route::post('consultar', [ChatbotApiController::class, 'consultar']);
});

// APIs públicas (webhooks, notificaciones)
Route::post('webhooks/firma-peru', function (Request $request) {
    $secret = config('services.firma_peru.webhook_secret');
    if ($secret) {
        $signature = $request->header('X-Firma-Peru-Signature', '');
        $expected  = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);
        if (!hash_equals($expected, $signature)) {
            abort(401, 'Firma inválida');
        }
    }
    \Log::info('Webhook Firma Perú:', $request->all());
    return response()->json(['status' => 'received']);
});

// RENIEC - Usar auth:web en lugar de auth:sanctum para usuarios web autenticados
Route::middleware(['auth:web'])->group(function () {
    Route::get('/reniec/consultar-dni/{dni}', [ReniecController::class, 'consultarDni']);
});