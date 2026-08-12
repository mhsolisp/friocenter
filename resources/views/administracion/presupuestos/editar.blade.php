<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto — {{ $turno->vehiculo->patente }} — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 40rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        .patente-chip { background: var(--navy); color: #fff; font-weight: 800; letter-spacing: 0.08em; padding: 0.5rem 1rem; border-radius: 8px; font-size: 1.2rem; }
        .volver { color: var(--navy); font-size: 0.85rem; text-decoration: none; font-weight: 600; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .card h2 { margin: 0 0 1rem; font-size: 1.05rem; color: var(--navy); }
        dt { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #5b6b78; margin-top: 0.75rem; }
        dt:first-child { margin-top: 0; }
        dd { margin: 0.1rem 0 0; font-weight: 600; white-space: pre-line; }
        .sin-orden { color: #b7791f; font-size: 0.88rem; }
        label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; }
        input[type=number] { width: 100%; padding: 0.65rem 0.75rem; border: 1px solid var(--line); border-radius: 8px; font-size: 1.1rem; }
        input:focus { outline: 2px solid var(--ice); }
        .monto-actual { font-size: 1.6rem; font-weight: 800; color: var(--navy); }
        .nota { font-size: 0.78rem; color: #5b6b78; margin-top: 0.4rem; }
        button.guardar { margin-top: 1rem; background: var(--navy); color: #fff; border: none; padding: 0.75rem 1.4rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        button.guardar:hover { background: #081a2e; }
        .respuesta-botones { display: flex; gap: 0.75rem; margin-top: 1rem; }
        button.aceptar { background: var(--ok-green); color: #fff; border: none; padding: 0.7rem 1.2rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        button.rechazar { background: #fff; color: var(--error); border: 1px solid var(--error); padding: 0.7rem 1.2rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .estado-final { font-weight: 700; }
        .estado-final.aceptado { color: var(--ok-green); }
        .estado-final.rechazado { color: var(--error); }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
    </style>
</head>
<body>
<header>
    <div class="brand">Frío Center · <span>Administración</span></div>
    <nav>
        <a href="{{ route('administracion.dashboard') }}">Inicio</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Salir ({{ auth()->user()->name }})</button>
        </form>
    </nav>
</header>
<main>
    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif

    <div class="top-row">
        <span class="patente-chip">{{ $turno->vehiculo->patente }}</span>
        <a href="{{ route('administracion.presupuestos.index') }}" class="volver">← Volver al listado</a>
    </div>

    <div class="card">
        <h2>Datos del vehículo</h2>
        <dt>Vehículo</dt>
        <dd>{{ $turno->vehiculo->descripcion ?: '—' }}</dd>
        <dt>Cliente</dt>
        <dd>{{ $turno->cliente->nombre_apellido }} · {{ $turno->cliente->telefono }}</dd>
        <dt>Problemática informada</dt>
        <dd>{{ $turno->problematica }}</dd>
    </div>

    <div class="card">
        <h2>Orden de Trabajo (cargada por Taller)</h2>

        @if ($turno->ordenTrabajo)
            <dt>Tareas a realizar</dt>
            <dd>{{ $turno->ordenTrabajo->tareas }}</dd>
            <dt>Repuestos necesarios</dt>
            <dd>{{ $turno->ordenTrabajo->repuestos ?: '—' }}</dd>
            <dt>Tiempo estimado</dt>
            <dd>{{ $turno->ordenTrabajo->tiempo_estimado ?: '—' }}</dd>
        @else
            <p class="sin-orden">Taller todavía no cargó la orden de trabajo para este vehículo.</p>
        @endif
    </div>

    <div class="card">
        <h2>Presupuesto</h2>

        @if (!$turno->presupuesto || $turno->presupuesto->estado === 'pendiente')
            @if ($turno->presupuesto)
                <p class="nota">Ya enviado el {{ $turno->presupuesto->fecha_envio->format('d/m/Y H:i') }} hs. Podés actualizar el monto mientras el cliente no haya respondido.</p>
            @endif

            <form action="{{ route('administracion.presupuestos.store', $turno) }}" method="POST">
                @csrf
                <label for="monto">Monto (neto + IVA)</label>
                <input type="number" name="monto" id="monto" step="0.01" min="0" required value="{{ old('monto', $turno->presupuesto->monto ?? '') }}">
                <p class="nota">Se informa como valor neto + IVA. Documento no válido como factura.</p>
                <button type="submit" class="guardar">{{ $turno->presupuesto ? 'Actualizar y reenviar' : 'Cargar y enviar presupuesto' }}</button>
            </form>

            @if ($turno->presupuesto)
                <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid var(--line);">
                <p class="nota" style="margin-bottom: 0.75rem;">Cuando el cliente responda (por teléfono o WhatsApp), registrá acá su decisión:</p>
                <div class="respuesta-botones">
                    <form action="{{ route('administracion.presupuestos.respuesta', $turno) }}" method="POST">
                        @csrf
                        <input type="hidden" name="respuesta" value="aceptado">
                        <button type="submit" class="aceptar">Cliente aceptó</button>
                    </form>
                    <form action="{{ route('administracion.presupuestos.respuesta', $turno) }}" method="POST">
                        @csrf
                        <input type="hidden" name="respuesta" value="rechazado">
                        <button type="submit" class="rechazar">Cliente rechazó</button>
                    </form>
                </div>
            @endif
        @else
            <div class="monto-actual">${{ number_format($turno->presupuesto->monto, 2, ',', '.') }}</div>
            <p class="nota">Enviado el {{ $turno->presupuesto->fecha_envio->format('d/m/Y H:i') }} hs.</p>
            <p class="estado-final {{ $turno->presupuesto->estado }}" style="margin-top: 0.75rem;">
                Cliente {{ $turno->presupuesto->estado }} el {{ $turno->presupuesto->fecha_respuesta?->format('d/m/Y H:i') }} hs.
            </p>
        @endif
    </div>
</main>
</body>
</html>
