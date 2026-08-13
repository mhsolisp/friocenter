<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            // Momento en que un usuario de Taller "toma" la orden para trabajarla.
            // Mientras sea null, la orden está pendiente en la cola de Taller.
            $table->dateTime('tomada_at')->nullable()->after('usuario_id');
        });

        // Ahora la orden se crea vacía apenas Administración confirma la recepción
        // del vehículo, antes de que Taller la tome y cargue el detalle.
        DB::statement('ALTER TABLE ordenes_trabajo MODIFY usuario_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE ordenes_trabajo MODIFY tareas TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE ordenes_trabajo MODIFY tareas TEXT NOT NULL');
        DB::statement('ALTER TABLE ordenes_trabajo MODIFY usuario_id BIGINT UNSIGNED NOT NULL');

        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            $table->dropColumn('tomada_at');
        });
    }
};
