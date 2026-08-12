<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            // Token único para el enlace de gestión que recibe el cliente por mail.
            $table->string('token', 64)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->text('problematica');
            $table->date('fecha_turno');
            $table->time('hora_turno');
            $table->enum('estado', [
                'reservado',
                'cancelado',
                'ingresado',
                'presupuestado',
                'aceptado',
                'rechazado',
                'cerrado',
            ])->default('reservado');
            $table->dateTime('fecha_ingreso_real')->nullable();
            $table->timestamps();

            $table->index(['fecha_turno', 'hora_turno']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
