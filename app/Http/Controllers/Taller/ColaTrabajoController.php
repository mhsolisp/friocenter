<?php

namespace App\Http\Controllers\Taller;

use App\Http\Controllers\Controller;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;

class ColaTrabajoController extends Controller
{
    /**
     * GET /taller/ordenes-trabajo
     * Cola de trabajo: órdenes que Administración generó al recibir un
     * vehículo y todavía nadie tomó, más las que están en curso.
     */
    public function index()
    {
        $pendientes = OrdenTrabajo::whereNull('tomada_at')
            ->whereHas('turno', fn ($q) => $q->where('estado', 'ingresado'))
            ->with(['turno.cliente', 'turno.vehiculo.modelo.marca'])
            ->orderBy('created_at')
            ->get();

        $enCurso = OrdenTrabajo::whereNotNull('tomada_at')
            ->whereHas('turno', fn ($q) => $q->where('estado', 'ingresado'))
            ->with(['turno.cliente', 'turno.vehiculo.modelo.marca', 'usuario'])
            ->orderBy('tomada_at')
            ->get();

        return view('taller.ordenes-trabajo.index', compact('pendientes', 'enCurso'));
    }

    /**
     * POST /taller/ordenes-trabajo/{orden}/tomar
     */
    public function tomar(Request $request, OrdenTrabajo $orden)
    {
        if ($orden->estaTomada()) {
            return back()->withErrors(['orden' => 'Esta orden ya fue tomada por '.$orden->usuario?->name.'.']);
        }

        $orden->update([
            'usuario_id' => $request->user()->id,
            'tomada_at' => now(),
        ]);

        return redirect()->route('taller.orden-trabajo.edit', $orden->turno);
    }
}
