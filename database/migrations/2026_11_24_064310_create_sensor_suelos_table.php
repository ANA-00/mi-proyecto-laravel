<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_suelos', function (Blueprint $table) {
            $table->id();
            $table->string('parcela');
            $table->decimal('humedad_porcentaje', 5, 2);
            $table->decimal('temperatura_suelo', 5, 2);
            $table->string('mensaje_alerta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_suelos');
    }
};