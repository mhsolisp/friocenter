<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('cuit', 15)->unique();
            $table->enum('condicion_fiscal', ['consumidor_final', 'factura'])->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('rubro_id')->nullable()->constrained('rubros_proveedor')->nullOnDelete();
            $table->string('direccion')->nullable();
            $table->text('observaciones')->nullable();
            // Baja lógica: nunca se borra un proveedor, para no perder el
            // historial de pagos ya registrados en Caja / Cuenta corriente.
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
