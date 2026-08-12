<?php

namespace App\Http\Controllers\Taller;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    /**
     * GET /taller/ingreso
     * Buscador de patente. Si viene ?patente=XXX en la URL, muestra
     * directamente el resultado (turno de hoy + historial). Así el botón
     * "Confirmar ingreso" puede redirigir acá mismo después de guardar.
     */
    public function index(Request $request)
    {
        $patente = $request->query('patente');

        if (! $patente) {
            return view('taller.ingreso.buscar');
        }

        $patente = strtoupper(trim($patente));

        $vehiculo = Vehiculo::with(['cliente', 'modelo.marca'])
            ->where('patente', $patente)
            ->first();

        if (! $vehiculo) {
            return view('taller.ingreso.buscar', [
                'noEncontrado' => true,
                'patenteBuscada' => $patente,
            ]);
        }

        // Turno de hoy para ese vehículo (el que corresponde ingresar ahora).
        $turnoHoy = Turno::where('vehiculo_id', $vehiculo->id)
            ->whereDate('fecha_turno', now()->toDateString())
            ->whereIn('estado', ['reservado', 'ingresado', 'presupuestado'])
            ->with('cliente')
            ->orderByDesc('created_at')
            ->first();

        // Historial de atenciones anteriores, solo si el usuario tiene el permiso.
        $historial = collect();
        $puedeVerHistorial = $request->user()->tienePermiso('ver_historial');

        if ($puedeVerHistorial) {
            $historial = Turno::where('vehiculo_id', $vehiculo->id)
                ->when($turnoHoy, fn ($q) => $q->where('id', '!=', $turnoHoy->id))
                ->with('cliente')
                ->orderByDesc('fecha_turno')
                ->orderByDesc('hora_turno')
                ->get();
        }

        return view('taller.ingreso.resultado', [
            'vehiculo' => $vehiculo,
            'turnoHoy' => $turnoHoy,
            'historial' => $historial,
            'puedeVerHistorial' => $puedeVerHistorial,
        ]);
    }

    /**
     * POST /taller/ingreso/{turno}/confirmar
     * Marca el ingreso real del vehículo al taller (llegó físicamente).
     */
    public function confirmar(Turno $turno)
    {
        $turno->update([
            'estado' => 'ingresado',
            'fecha_ingreso_real' => now(),
        ]);

        return redirect()
            ->route('taller.ingreso.index', ['patente' => $turno->vehiculo->patente])
            ->with('ok', 'Ingreso confirmado. El vehículo ya está en el taller.');
    }
}
