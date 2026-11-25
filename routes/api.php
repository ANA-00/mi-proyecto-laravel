<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RiegoController;

// Endpoint de diagnóstico (Health Check)
Route::get('/v1/estado', function () {
    return response()->json([
        'status' => 200,
        'servicio' => 'AgroExporta Olmos API',
        'estado' => 'Servidor Operativo 100%',
        'framework' => app()->version()
    ], 200);
});

// Rutas CRUD para el recurso /api/v1/riego
Route::apiResource('/v1/riego', RiegoController::class);