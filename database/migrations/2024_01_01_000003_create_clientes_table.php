<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_apellido');
            $table->enum('condicion_fiscal', ['consumidor_final', 'factura'])->default('consumidor_final');
            $table->string('dni', 15)->nullable();
            $table->string('cuit', 15)->nullable();
            $table->string('razon_social')->nullable();
            $table->enum('condicion_iva', [
                'responsable_inscripto',
                'monotributo',
                'exento',
                'consumidor_final',
            ])->nullable();
            $table->string('telefono');
            $table->string('email');
            $table->timestamps();

            $table->index('telefono');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
