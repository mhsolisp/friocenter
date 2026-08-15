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
                        <div style="color:#ffffff; font-size:1.2rem; font-weight:700; margin-top:0.3rem;">Tu presupuesto</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 1.75rem 2rem;">
                        <p style="margin:0 0 1rem; font-size:0.95rem; line-height:1.5;">
                            Hola {{ $turno->cliente->nombre_apellido }}, te adjuntamos el presupuesto para tu
                            {{ $turno->vehiculo->descripcion ?: 'vehículo' }} (patente {{ $turno->vehiculo->patente }}).
                        </p>
                        <p style="margin:0 0 1rem; font-size:0.95rem; line-height:1.5;">
                            Los valores son netos + IVA. Recordá que este documento no es válido como factura,
                            es de uso interno del taller.
                        </p>
                        <p style="margin:0; font-size:0.95rem; line-height:1.5;">
                            Cualquier consulta, respondé este correo o escribinos por WhatsApp. ¡Gracias!
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
