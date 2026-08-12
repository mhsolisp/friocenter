<?php

namespace App\Http\Controllers\Taller;

use App\Http\Controllers\Controller;
use App\Models\Turno;

class TurnosProgramadosController extends Controller
{
    public function index()
    {
        $turnos = Turno::whereDate('fecha_turno', '>', now()->toDateString())
            ->where('estado', 'reservado')
            ->with(['cliente', 'vehiculo.modelo.marca'])
            ->orderBy('fecha_turno')
            ->orderBy('hora_turno')
            ->paginate(30);

        return view('taller.turnos-programados', compact('turnos'));
    }
}
