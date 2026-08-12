<?php

namespace Database\Seeders;

use App\Models\ConfiguracionAgenda;
use App\Models\Marca;
use Illuminate\Database\Seeder;

class TurnosDatosIniciales extends Seeder
{
    /**
     * Ejecutar con: php artisan db:seed --class=TurnosDatosIniciales
     */
    public function run(): void
    {
        $marcasYModelos = [
            'Ford' => ['Ka', 'Fiesta', 'Focus', 'Ranger', 'EcoSport'],
            'Chevrolet' => ['Onix', 'Cruze', 'S10', 'Corsa', 'Tracker'],
            'Volkswagen' => ['Gol', 'Polo', 'Amarok', 'Vento', 'Bora'],
            'Renault' => ['Sandero', 'Logan', 'Duster', 'Kangoo', 'Clio'],
            'Fiat' => ['Cronos', 'Argo', 'Toro', 'Strada', 'Mobi'],
            'Peugeot' => ['208', '308', '2008', 'Partner'],
            'Toyota' => ['Hilux', 'Corolla', 'Etios', 'SW4'],
            'Citroën' => ['C3', 'C4 Lounge', 'Berlingo'],
            'Nissan' => ['Frontier', 'Versa', 'Kicks'],
        ];

        foreach ($marcasYModelos as $nombreMarca => $modelos) {
            $marca = Marca::firstOrCreate(['nombre' => $nombreMarca]);
            foreach ($modelos as $nombreModelo) {
                $marca->modelos()->firstOrCreate(['nombre' => $nombreModelo]);
            }
        }

        // Agenda por defecto: lunes a viernes, 8 a 12 hs, turnos cada 15 min, 1 por franja
        // (8 franjas posibles entre 8:00 y 12:00 con intervalos de 15 min -> ajustable por Administración).
        foreach ([1, 2, 3, 4, 5] as $diaSemana) { // 1=lunes ... 5=viernes
            ConfiguracionAgenda::firstOrCreate(
                ['dia_semana' => $diaSemana],
                [
                    'hora_inicio' => '08:00',
                    'hora_fin' => '12:00',
                    'intervalo_minutos' => 15,
                    'turnos_por_franja' => 1,
                    'activo' => true,
                ]
            );
        }
    }
}
