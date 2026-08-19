<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_cuenta_corriente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->date('fecha');
            // "cargo" = queda debiendo (aumenta el saldo), "pago" = abona contra la deuda.
            $table->enum('tipo', ['cargo', 'pago']);
            $table->decimal('monto', 12, 2);
            $table->string('concepto', 150);
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_cuenta_corriente');
    }
};
