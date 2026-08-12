<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('patente')->unique();
            $table->foreignId('modelo_id')->nullable()->constrained('modelos')->nullOnDelete();
            // Si el cliente eligió "Otro" en marca/modelo, queda registrado acá como texto libre.
            $table->string('vehiculo_otro')->nullable();
            $table->unsignedSmallInteger('anio')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
