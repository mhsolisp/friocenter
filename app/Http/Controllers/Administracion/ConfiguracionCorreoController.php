<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionCorreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConfiguracionCorreoController extends Controller
{
    /**
     * GET /administracion/configuracion/correo
     */
    public function edit()
    {
        $configuracion = ConfiguracionCorreo::actual();

        return view('administracion.configuracion.correo', compact('configuracion'));
    }

    /**
     * POST /administracion/configuracion/correo
     */
    public function update(Request $request)
    {
        $datos = $request->validate([
            'mail_host' => 'required|string|max:150',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|string|max:150',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'required|email|max:150',
            'mail_from_name' => 'required|string|max:100',
        ]);

        $configuracion = ConfiguracionCorreo::actual();

        // Si no cargó una contraseña nueva, se mantiene la que ya había.
        if (empty($datos['mail_password'])) {
            unset($datos['mail_password']);
        }

        $configuracion->update($datos);

        return redirect()
            ->route('administracion.configuracion.correo')
            ->with('ok', 'Configuración de correo guardada.');
    }

    /**
     * POST /administracion/configuracion/correo/probar
     * Envía un correo de prueba con los datos ya guardados, para
     * confirmar que la configuración SMTP funciona.
     */
    public function probar(Request $request)
    {
        $datos = $request->validate([
            'destinatario' => 'required|email',
        ]);

        $configuracion = ConfiguracionCorreo::actual();

        if (! $configuracion->estaConfigurado()) {
            return back()->withErrors(['destinatario' => 'Primero guardá la configuración de correo.']);
        }

        try {
            Mail::raw(
                'Este es un correo de prueba enviado desde el sistema de turnos de Frío Center. Si lo recibiste, la configuración de correo está funcionando correctamente.',
                function ($message) use ($datos) {
                    $message->to($datos['destinatario'])->subject('Correo de prueba — Frío Center');
                }
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['destinatario' => 'No se pudo enviar: '.$e->getMessage()]);
        }

        return back()->with('ok', 'Correo de prueba enviado a '.$datos['destinatario'].'. Revisá la bandeja de entrada (y spam).');
    }
}
