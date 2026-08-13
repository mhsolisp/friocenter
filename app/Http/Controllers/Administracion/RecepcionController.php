<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class RecepcionController extends Controller
{
    /**
     * GET /administracion/recibir
     * Dos formas de confirmar la recepción: eligiendo de la lista de
     * turnos de hoy, o buscando directamente por patente.
     */
    public function index(Request $request)
    {
        $turnosHoy = Turno::whereDate('fecha_turno', now()->toDateString())
            ->where('estado', 'reservado')
            ->with(['cliente', 'vehiculo.modelo.marca'])
            ->orderBy('hora_turno')
            ->get();

        $patente = $request->query('patente');
        $resultadoPatente = null;
        $noEncontrado = false;

        if ($patente) {
            $patente = strtoupper(trim($patente));
            $vehiculo = Vehiculo::where('patente', $patente)->first();

            if (! $vehiculo) {
                $noEncontrado = true;
            } else {
                $resultadoPatente = Turno::where('vehiculo_id', $vehiculo->id)
                    ->whereDate('fecha_turno', now()->toDateString())
                    ->with(['cliente', 'vehiculo.modelo.marca'])
                    ->orderByDesc('created_at')
                    ->first();

                if (! $resultadoPatente) {
                    $noEncontrado = true;
                }
            }
        }

        return view('administracion.recibir.index', [
            'turnosHoy' => $turnosHoy,
            'patenteBuscada' => $patente,
            'resultadoPatente' => $resultadoPatente,
            'noEncontrado' => $noEncontrado,
        ]);
    }

    /**
     * POST /administracion/recibir/{turno}/confirmar
     * Confirma que el vehículo llegó físicamente: marca el turno como
     * "ingresado" y genera la orden de trabajo vacía para que Taller la tome.
     */
    public function confirmar(Turno $turno)
    {
        if ($turno->estado !== 'reservado') {
            return back()->withErrors(['turno' => 'Este turno ya fue recibido antes, o no está en condiciones de recibirse (estado actual: '.$turno->estado.').']);
        }

        $turno->update([
            'estado' => 'ingresado',
            'fecha_ingreso_real' => now(),
        ]);

        // Orden de trabajo vacía: Taller la va a "tomar" y cargar el detalle.
        $turno->ordenTrabajo()->firstOrCreate(['turno_id' => $turno->id]);

        return redirect()
            ->route('administracion.recibir.index')
            ->with('ok', 'Recepción confirmada para la patente '.$turno->vehiculo->patente.'. Ya está disponible en la cola de Taller.');
    }
}
