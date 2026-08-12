<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionAgenda extends Model
{
    protected $table = 'configuracion_agenda';

    protected $fillable = [
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'intervalo_minutos',
        'turnos_por_franja',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
