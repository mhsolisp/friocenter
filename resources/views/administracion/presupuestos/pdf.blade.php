<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 28px 34px; }
        * { box-sizing: border-box; }
        body { font-family: Helvetica, Arial, sans-serif; color: #16222e; font-size: 12px; }

        table.encabezado { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.encabezado td { vertical-align: top; }
        .logo-texto { font-size: 26px; font-weight: bold; color: #0f2a47; letter-spacing: 1px; }
        .logo-texto .center { color: #3ea8c9; }
        .logo-sub { font-size: 10px; color: #5b6b78; letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }

        .numero-box { text-align: right; }
        .numero-box .titulo { font-size: 16px; font-weight: bold; color: #0f2a47; text-transform: uppercase; letter-spacing: 1px; }
        .numero-box .numero { font-size: 20px; font-weight: bold; color: #0f2a47; margin-top: 4px; }
        .numero-box .fecha { font-size: 11px; color: #5b6b78; margin-top: 2px; }

        .sello {
            border: 2px solid #0f2a47;
            border-radius: 4px;
            padding: 6px 10px;
            display: inline-block;
            margin-top: 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f2a47;
            text-align: center;
            line-height: 1.4;
        }

        hr { border: none; border-top: 2px solid #0f2a47; margin: 10px 0 16px; }

        table.datos { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.datos td { padding: 7px 0; border-bottom: 1px solid #dde6ea; font-size: 12px; }
        table.datos td.etiqueta { width: 130px; color: #5b6b78; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; font-weight: bold; vertical-align: top; padding-top: 9px; }
        table.datos td.valor { font-weight: normal; }

        .observaciones-box { margin-top: 4px; margin-bottom: 18px; }
        .observaciones-box .etiqueta { color: #5b6b78; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; font-weight: bold; margin-bottom: 4px; }
        .observaciones-box .texto { border: 1px solid #dde6ea; border-radius: 4px; padding: 10px; min-height: 40px; font-size: 11px; line-height: 1.5; }

        table.monto-final { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.monto-final td { padding: 14px 16px; }
        .monto-final .caja { background: #eaf6fa; border-radius: 6px; text-align: right; }
        .monto-final .etiqueta { font-size: 11px; color: #5b6b78; text-transform: uppercase; letter-spacing: 0.5px; }
        .monto-final .valor { font-size: 26px; font-weight: bold; color: #0f2a47; margin-top: 2px; }

        .pie { margin-top: 22px; font-size: 9px; color: #5b6b78; line-height: 1.6; }
        .pie strong { color: #16222e; }
    </style>
</head>
<body>
    <table class="encabezado">
        <tr>
            <td style="width: 60%;">
                <div class="logo-texto">FRÍO <span class="center">CENTER</span></div>
                <div class="logo-sub">Rosario · Aire acondicionado</div>
                <div class="sello">
                    X Documento no válido como factura<br>
                    Documento de uso interno
                </div>
            </td>
            <td class="numero-box" style="width: 40%;">
                <div class="titulo">Presupuesto</div>
                <div class="numero">N° {{ $turno->presupuesto->numero_completo ?? '—' }}</div>
                <div class="fecha">Fecha: {{ ($turno->presupuesto->fecha_envio ?? $turno->presupuesto->created_at)->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <hr>

    <table class="datos">
        <tr>
            <td class="etiqueta">Nombre y Apellido</td>
            <td class="valor">{{ $turno->cliente->nombre_apellido }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Teléfono</td>
            <td class="valor">{{ $turno->cliente->telefono }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Vehículo</td>
            <td class="valor">
                {{ $turno->vehiculo->descripcion ?: '—' }}
                @if ($turno->vehiculo->anio) ({{ $turno->vehiculo->anio }}) @endif
                @if ($turno->vehiculo->color) — {{ $turno->vehiculo->color }} @endif
            </td>
        </tr>
        <tr>
            <td class="etiqueta">Patente</td>
            <td class="valor">{{ $turno->vehiculo->patente }}</td>
        </tr>
    </table>

    <div class="observaciones-box">
        <div class="etiqueta">Observaciones</div>
        <div class="texto">
            {{ $turno->problematica ?: 'Sin detalle informado.' }}
            @if ($turno->ordenTrabajo && $turno->ordenTrabajo->tareas)
                <br><br><strong>Tareas realizadas por el taller:</strong><br>{{ $turno->ordenTrabajo->tareas }}
            @endif
        </div>
    </div>

    <table class="monto-final">
        <tr>
            <td class="caja">
                <div class="etiqueta">Presupuesto (neto + IVA)</div>
                <div class="valor">${{ number_format($turno->presupuesto->monto, 2, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <div class="pie">
        <strong>Frío Center — Rosario.</strong>
        Los valores informados son netos + IVA. Este documento es de uso interno del taller y no es válido
        como factura. Presupuesto sujeto a modificación si, al desarmar el equipo, se detectan fallas
        adicionales no visibles al momento del diagnóstico.
    </div>
</body>
</html>
