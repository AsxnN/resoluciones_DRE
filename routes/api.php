<?php
// filepath: routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    // Procesar callback de Firma Perú
    \Log::info('Webhook Firma Perú:', $request->all());
    return response()->json(['status' => 'received']);
});