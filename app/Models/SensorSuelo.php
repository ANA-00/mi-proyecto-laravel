<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorSuelo extends Model
{
    use HasFactory;

    protected $table = 'sensor_suelos';

    protected $fillable = [
        'parcela',
        'humedad_porcentaje',
        'temperatura_suelo',
        'mensaje_alerta',
    ];
}