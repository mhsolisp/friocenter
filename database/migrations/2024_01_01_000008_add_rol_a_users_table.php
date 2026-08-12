<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['administracion', 'taller'])->default('taller')->after('email');

            // Permisos independientes dentro del rol Taller (no aplican a Administración,
            // que ya tiene acceso a todo). Se pueden combinar libremente.
            $table->boolean('permiso_ver_dia')->default(true)->after('rol');
            $table->boolean('permiso_ver_dias_programados')->default(false)->after('permiso_ver_dia');
            $table->boolean('permiso_ver_historial')->default(false)->after('permiso_ver_dias_programados');

            $table->boolean('activo')->default(true)->after('permiso_ver_historial');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'rol',
                'permiso_ver_dia',
                'permiso_ver_dias_programados',
                'permiso_ver_historial',
                'activo',
            ]);
        });
    }
};
