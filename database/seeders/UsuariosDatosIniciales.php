<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsuariosDatosIniciales extends Seeder
{
    /**
     * Ejecutar con: php artisan db:seed --class=UsuariosDatosIniciales
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@friocenter.com'],
            [
                'name' => 'Administración',
                'password' => 'password',
                'rol' => 'administracion',
                'activo' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'romina@friocenter.com.ar'],
            [
                'name' => 'Romina',
                'password' => 'password',
                'rol' => 'administracion',
                'activo' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'taller@friocenter.com'],
            [
                'name' => 'Taller',
                'password' => 'password',
                'rol' => 'taller',
                'permiso_ver_dia' => true,
                'permiso_ver_dias_programados' => true,
                'permiso_ver_historial' => true,
                'activo' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'victor@friocenter.com'],
            [
                'name' => 'Victor',
                'password' => 'password',
                'rol' => 'taller',
                'permiso_ver_dia' => true,
                'permiso_ver_dias_programados' => true,
                'permiso_ver_historial' => true,
                'activo' => true,
            ]
        );
    }
}
