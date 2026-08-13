<?php

namespace App\Http\Controllers\Taller;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;

class OrdenTrabajoController extends Controller
{
    /**
     * GET /taller/turnos/{turno}/orden-trabajo
     * Formulario para cargar/editar la Orden de Trabajo. Nunca incluye
     * ningún dato de presupuesto ($): eso es exclusivo de Administración.
     */
    public function edit(Turno $turno)
    {
        if ($turno->estado === 'reservado') {
            abort(403, 'El vehículo todavía no ingresó al taller.');
        }

        $turno->load(['cliente', 'vehiculo.modelo.marca', 'ordenTrabajo.usuario']);

        if (! $turno->ordenTrabajo || ! $turno->ordenTrabajo->estaTomada()) {
            return redirect()
                ->route('taller.ordenes-trabajo.index')
                ->withErrors(['orden' => 'Primero tenés que tomar esta orden de trabajo desde la cola.']);
        }

        return view('taller.orden-trabajo.editar', compact('turno'));
    }

    /**
     * POST /taller/turnos/{turno}/orden-trabajo
     */
    public function update(Request $request, Turno $turno)
    {
        if ($turno->estado === 'reservado') {
            abort(403, 'El vehículo todavía no ingresó al taller.');
        }

        $datos = $request->validate([
            'tareas' => 'required|string|max:2000',
            'repuestos' => 'nullable|string|max:2000',
            'tiempo_estimado' => 'nullable|string|max:100',
        ]);

        $turno->ordenTrabajo()->updateOrCreate(
            ['turno_id' => $turno->id],
            $datos
        );

        return redirect()
            ->route('taller.orden-trabajo.edit', $turno)
            ->with('ok', 'Orden de trabajo guardada.');
    }
}
