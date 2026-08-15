<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; background:#f5f8fa; font-family: -apple-system, 'Segoe UI', Roboto, sans-serif; color:#16222e;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5f8fa; padding: 2rem 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; border:1px solid #dde6ea;">
                <tr>
                    <td style="background:#0f2a47; padding: 1.5rem 2rem;">
                        <div style="color:#3ea8c9; font-size:0.75rem; font-weight:700; letter-spacing:0.14em; text-transform:uppercase;">Frío Center · Rosario</div>
                        <div style="color:#ffffff; font-size:1.2rem; font-weight:700; margin-top:0.3rem;">¡Turno confirmado!</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 1.75rem 2rem;">
                        <p style="margin:0 0 1rem; font-size:0.95rem; line-height:1.5;">
                            Hola {{ $turno->cliente->nombre_apellido }}, confirmamos tu turno en Frío Center para traer tu vehículo a diagnóstico.
                        </p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eaf6fa; border-radius:8px; margin-bottom:1.25rem;">
                            <tr>
                                <td style="padding: 1rem 1.25rem; font-size:0.9rem;">
                                    <strong>Fecha:</strong> {{ $turno->fecha_turno->format('d/m/Y') }}<br>
                                    <strong>Hora:</strong> {{ \Carbon\Carbon::parse($turno->hora_turno)->format('H:i') }} hs<br>
                                    <strong>Vehículo:</strong> {{ $turno->vehiculo->descripcion ?: '—' }} ({{ $turno->vehiculo->patente }})
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 1.25rem; font-size:0.9rem; line-height:1.5;">
                            Si necesitás cancelar o reprogramar, podés hacerlo desde este enlace en cualquier momento hasta las 14 hs del día anterior al turno:
                        </p>

                        <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:1.5rem;">
                            <tr>
                                <td style="background:#0f2a47; border-radius:8px;">
                                    <a href="{{ route('turnos.gestionar', $turno->token) }}" style="display:inline-block; padding:0.75rem 1.5rem; color:#ffffff; text-decoration:none; font-weight:700; font-size:0.9rem;">
                                        Gestionar mi turno
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0; font-size:0.78rem; color:#5b6b78;">
                            Si el botón no funciona, copiá y pegá este enlace en tu navegador:<br>
                            {{ route('turnos.gestionar', $turno->token) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
