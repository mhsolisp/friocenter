<?php

namespace App\Http\Controllers\Taller;

use App\Http\Controllers\Controller;
use App\Models\Turno;

class TurnosHoyController extends Controller
{
    public function index()
    {
        $turnos = Turno::whereDate('fecha_turno', now()->toDateString())
            ->where('estado', '!=', 'cancelado')
            ->with(['cliente', 'vehiculo.modelo.marca'])
            ->orderBy('hora_turno')
            ->get();

        return view('taller.turnos-hoy', compact('turnos'));
    }
}
