<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorSuelo;
use Illuminate\Http\Request;

class RiegoController extends Controller
{
    /**
     * Regla de negocio: Calcula el mensaje de alerta según los 3 umbrales de humedad
     */
    private function calcularAlerta(float $humedad): string
    {
        if ($humedad < 25) {
            return 'ALERTA: RIEGO URGENTE';
        } elseif ($humedad >= 25 && $humedad <= 45) {
            return 'HUMEDAD OPTIMA';
        } else {
            return 'ALERTA: EXCESO DE AGUA';
        }
    }

    // GET /api/v1/riego -> Listar todo el historial (200 OK)
    public function index()
    {
        $registros = SensorSuelo::all();
        return response()->json([
            'status' => 200,
            'empresa' => 'AgroExporta Olmos',
            'data' => $registros
        ], 200);
    }

    // POST /api/v1/riego -> Crear un nuevo registro (201 Created o 422 Unprocessable Entity)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parcela' => 'required|string|max:100',
            'humedad_porcentaje' => 'required|numeric|min:0|max:100',
            'temperatura_suelo' => 'required|numeric|min:-10|max:60',
        ]);

        $validated['mensaje_alerta'] = $this->calcularAlerta($validated['humedad_porcentaje']);

        $registro = SensorSuelo::create($validated);

        return response()->json([
            'status' => 201,
            'mensaje' => 'Lectura de sensor registrada correctamente',
            'data' => $registro
        ], 201);
    }

    // GET /api/v1/riego/{id} -> Buscar por ID (200 OK o 404 Not Found)
    public function show(string $id)
    {
        $registro = SensorSuelo::find($id);

        if (!$registro) {
            return response()->json([
                'status' => 404,
                'mensaje' => 'Registro de sensor no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $registro
        ], 200);
    }

    // PUT /api/v1/riego/{id} -> Actualizar y recalcular mensaje_alerta (200 OK o 404 Not Found)
    public function update(Request $request, string $id)
    {
        $registro = SensorSuelo::find($id);

        if (!$registro) {
            return response()->json([
                'status' => 404,
                'mensaje' => 'Registro de sensor no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'parcela' => 'sometimes|required|string|max:100',
            'humedad_porcentaje' => 'required|numeric|min:0|max:100',
            'temperatura_suelo' => 'sometimes|required|numeric|min:-10|max:60',
        ]);

        // Recalcular el umbral de alerta con el nuevo valor de humedad
        $validated['mensaje_alerta'] = $this->calcularAlerta($validated['humedad_porcentaje']);

        $registro->update($validated);

        return response()->json([
            'status' => 200,
            'mensaje' => 'Lectura actualizada y alerta recalculada',
            'data' => $registro
        ], 200);
    }

    // DELETE /api/v1/riego/{id} -> Eliminar registro (200 OK o 404 Not Found)
    public function destroy(string $id)
    {
        $registro = SensorSuelo::find($id);

        if (!$registro) {
            return response()->json([
                'status' => 404,
                'mensaje' => 'Registro de sensor no encontrado'
            ], 404);
        }

        $registro->delete();

        return response()->json([
            'status' => 200,
            'mensaje' => 'Registro eliminado correctamente'
        ], 200);
    }
}