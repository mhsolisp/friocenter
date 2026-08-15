<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Mail\PresupuestoPdfMail;
use App\Models\Turno;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PresupuestoPdfController extends Controller
{
    /**
     * GET /administracion/turnos/{turno}/presupuesto/pdf
     * Genera el PDF del presupuesto y lo descarga.
     */
    public function descargar(Turno $turno)
    {
        $turno->load(['cliente', 'vehiculo.modelo.marca', 'presupuesto', 'ordenTrabajo']);

        abort_unless($turno->presupuesto, 404, 'Este turno todavía no tiene un presupuesto cargado.');

        $pdf = Pdf::loadView('administracion.presupuestos.pdf', ['turno' => $turno]);

        $nombreArchivo = 'presupuesto-'.($turno->presupuesto->numero_completo ?? $turno->id).'.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * POST /administracion/turnos/{turno}/presupuesto/pdf/enviar
     * Genera el PDF y lo manda por mail al cliente como adjunto, usando la
     * configuración de correo cargada en /administracion/configuracion/correo.
     */
    public function enviar(Request $request, Turno $turno)
    {
        $turno->load(['cliente', 'vehiculo.modelo.marca', 'presupuesto', 'ordenTrabajo']);

        abort_unless($turno->presupuesto, 404, 'Este turno todavía no tiene un presupuesto cargado.');

        if (! $turno->cliente->email) {
            return back()->withErrors(['pdf' => 'El cliente no tiene un email cargado.']);
        }

        $pdf = Pdf::loadView('administracion.presupuestos.pdf', ['turno' => $turno]);
        $nombreArchivo = 'presupuesto-'.($turno->presupuesto->numero_completo ?? $turno->id).'.pdf';

        try {
            Mail::to($turno->cliente->email)->send(
                new PresupuestoPdfMail($turno, $pdf->output(), $nombreArchivo)
            );
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['pdf' => 'No se pudo enviar el correo: '.$e->getMessage()]);
        }

        return back()->with('ok', 'Presupuesto enviado por correo a '.$turno->cliente->email.'.');
    }
}
