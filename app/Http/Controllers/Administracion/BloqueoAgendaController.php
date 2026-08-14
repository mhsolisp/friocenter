<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\BloqueoAgenda;
use Illuminate\Http\Request;

class BloqueoAgendaController extends Controller
{
    /**
     * GET /administracion/agenda/bloqueos
     */
    public function index()
    {
        $bloqueos = BloqueoAgenda::orderByDesc('fecha_inicio')->get();

        return view('administracion.agenda.bloqueos', compact('bloqueos'));
    }

    /**
     * POST /administracion/agenda/bloqueos
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'nullable|string|max:150',
        ]);

        BloqueoAgenda::create([...$datos, 'usuario_id' => $request->user()->id]);

        return redirect()
            ->route('administracion.agenda.bloqueos')
            ->with('ok', 'Bloqueo agregado. No se van a ofrecer turnos en ese rango de fechas.');
    }

    /**
     * DELETE /administracion/agenda/bloqueos/{bloqueo}
     */
    public function destroy(BloqueoAgenda $bloqueo)
    {
        $bloqueo->delete();

        return redirect()
            ->route('administracion.agenda.bloqueos')
            ->with('ok', 'Bloqueo eliminado.');
    }
}
