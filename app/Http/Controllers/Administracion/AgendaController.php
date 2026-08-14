<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionAgenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    protected const NOMBRES_DIAS = [
        0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
        4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado',
    ];

    /**
     * GET /administracion/agenda
     * Un renglón por día de la semana (0=domingo..6=sábado), editable en
     * un solo formulario.
     */
    public function index()
    {
        $configuraciones = ConfiguracionAgenda::all()->keyBy('dia_semana');

        $dias = collect(self::NOMBRES_DIAS)->map(function ($nombre, $numero) use ($configuraciones) {
            return (object) [
                'numero' => $numero,
                'nombre' => $nombre,
                'config' => $configuraciones->get($numero),
            ];
        });

        return view('administracion.agenda.index', compact('dias'));
    }

    /**
     * POST /administracion/agenda
     * Guarda los 7 días de una sola vez.
     */
    public function update(Request $request)
    {
        $datos = $request->validate([
            'dias' => 'required|array',
            'dias.*.activo' => 'nullable|boolean',
            'dias.*.hora_inicio' => 'required_with:dias.*.activo|nullable|date_format:H:i',
            'dias.*.hora_fin' => 'required_with:dias.*.activo|nullable|date_format:H:i|after:dias.*.hora_inicio',
            'dias.*.intervalo_minutos' => 'required|integer|min:5|max:240',
            'dias.*.turnos_por_franja' => 'required|integer|min:1|max:50',
        ]);

        foreach ($datos['dias'] as $diaSemana => $fila) {
            $activo = (bool) ($fila['activo'] ?? false);

            ConfiguracionAgenda::updateOrCreate(
                ['dia_semana' => $diaSemana],
                [
                    'hora_inicio' => $fila['hora_inicio'] ?? '08:00',
                    'hora_fin' => $fila['hora_fin'] ?? '12:00',
                    'intervalo_minutos' => $fila['intervalo_minutos'],
                    'turnos_por_franja' => $fila['turnos_por_franja'],
                    'activo' => $activo,
                ]
            );
        }

        return redirect()
            ->route('administracion.agenda.index')
            ->with('ok', 'Configuración de agenda actualizada.');
    }
}
