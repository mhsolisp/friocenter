<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    /**
     * GET /administracion/presupuestos
     * Cola de trabajo: vehículos ya ingresados (con o sin orden de trabajo
     * cargada por Taller) que todavía no tienen un presupuesto enviado o
     * que están esperando la respuesta del cliente.
     */
    public function index()
    {
        $turnos = Turno::whereIn('estado', ['ingresado', 'presupuestado'])
            ->with(['cliente', 'vehiculo.modelo.marca', 'ordenTrabajo', 'presupuesto'])
            ->orderBy('fecha_turno')
            ->orderBy('hora_turno')
            ->get();

        return view('administracion.presupuestos.index', compact('turnos'));
    }

    /**
     * GET /administracion/turnos/{turno}/presupuesto
     * Muestra la orden de trabajo cargada por Taller (solo lectura) y el
     * formulario para cargar/enviar el presupuesto, o para registrar la
     * respuesta del cliente si ya se envió.
     */
    public function edit(Turno $turno)
    {
        if ($turno->estado === 'reservado' || $turno->estado === 'cancelado') {
            abort(403, 'Este turno todavía no tiene un vehículo ingresado, o fue cancelado.');
        }

        $turno->load(['cliente', 'vehiculo.modelo.marca', 'ordenTrabajo', 'presupuesto']);

        return view('administracion.presupuestos.editar', compact('turno'));
    }

    /**
     * POST /administracion/turnos/{turno}/presupuesto
     * Carga (o actualiza, mientras esté pendiente) el monto y lo marca
     * como enviado al cliente.
     */
    public function store(Request $request, Turno $turno)
    {
        $datos = $request->validate([
            'monto' => 'required|numeric|min:0',
        ]);

        $turno->presupuesto()->updateOrCreate(
            ['turno_id' => $turno->id],
            [
                'monto' => $datos['monto'],
                'usuario_id' => $request->user()->id,
                'estado' => 'pendiente',
                'fecha_envio' => now(),
            ]
        );

        $turno->update(['estado' => 'presupuestado']);

        // TODO (próxima etapa): notificar al cliente por WhatsApp/email que el
        // presupuesto está disponible (recordar: valores netos + IVA, leyenda
        // "Documento no válido como factura").

        return redirect()
            ->route('administracion.presupuestos.edit', $turno)
            ->with('ok', 'Presupuesto guardado y marcado como enviado.');
    }

    /**
     * POST /administracion/turnos/{turno}/presupuesto/respuesta
     * Registra la respuesta del cliente (comunicada por teléfono/WhatsApp,
     * ya que el cliente no tiene una pantalla propia para aceptar/rechazar).
     */
    public function registrarRespuesta(Request $request, Turno $turno)
    {
        $datos = $request->validate([
            'respuesta' => 'required|in:aceptado,rechazado',
        ]);

        $turno->presupuesto()->update([
            'estado' => $datos['respuesta'],
            'fecha_respuesta' => now(),
        ]);

        // Presupuesto aceptado → el taller toma el caso (orden de trabajo ya cargada).
        // Rechazado → el ticket queda cerrado, registrado en el historial.
        $turno->update([
            'estado' => $datos['respuesta'] === 'aceptado' ? 'aceptado' : 'cerrado',
        ]);

        return redirect()
            ->route('administracion.presupuestos.index')
            ->with('ok', 'Respuesta del cliente registrada.');
    }
}
