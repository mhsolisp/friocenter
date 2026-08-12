<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_agenda', function (Blueprint $table) {
            $table->id();
            // 0 = domingo ... 6 = sábado (así lo devuelve Carbon::dayOfWeek)
            $table->unsignedTinyInteger('dia_semana');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->unsignedSmallInteger('intervalo_minutos')->default(15);
            $table->unsignedTinyInteger('turnos_por_franja')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('dia_semana');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_agenda');
    }
};
