<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            // Ejercicio anual del taller: de septiembre a agosto. Se guarda
            // como "9" + año inicio (2 dígitos) + "8" + año fin (2 dígitos).
            // Ej.: septiembre/2024 a agosto/2025 -> "924825".
            $table->string('numero_ejercicio', 6)->nullable()->after('id');
            // Correlativo dentro de ese ejercicio (vuelve a empezar en 1
            // cada nuevo ejercicio). El número completo que se imprime es
            // "{numero_ejercicio}-{numero_correlativo con 4 dígitos}",
            // igual que en las boletas de papel que ya usa el taller.
            $table->unsignedInteger('numero_correlativo')->nullable()->after('numero_ejercicio');
            $table->unique(['numero_ejercicio', 'numero_correlativo']);
        });
    }

    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropUnique(['numero_ejercicio', 'numero_correlativo']);
            $table->dropColumn(['numero_ejercicio', 'numero_correlativo']);
        });
    }
};
