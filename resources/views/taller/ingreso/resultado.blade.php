<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $vehiculo->patente }} — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --error: #c0392b; --ok-green: #2e7d32; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 42rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }

        .patente-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .patente-chip { background: var(--navy); color: #fff; font-weight: 800; letter-spacing: 0.08em; padding: 0.5rem 1rem; border-radius: 8px; font-size: 1.2rem; }
        .volver { color: var(--navy); font-size: 0.85rem; text-decoration: none; font-weight: 600; }

        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .card h2 { margin: 0 0 1rem; font-size: 1.05rem; color: var(--navy); }

        dt { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #5b6b78; margin-top: 0.75rem; }
        dt:first-child { margin-top: 0; }
        dd { margin: 0.1rem 0 0; font-weight: 600; color: #16222e; }

        .estado-badge { display: inline-block; padding: 0.25rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; background: var(--ice-light); color: var(--navy); margin-bottom: 0.75rem; }

        .sin-turno { color: #5b6b78; font-size: 0.9rem; }

        button.confirmar { background: var(--ok-green); color: #fff; border: none; padding: 0.7rem 1.3rem; border-radius: 8px; font-weight: 700; cursor: pointer; margin-top: 1rem; }
        button.confirmar:hover { opacity: 0.9; }

        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        .historial-item { border-top: 1px solid var(--line); padding: 0.85rem 0; }
        .historial-item:first-child { border-top: none; padding-top: 0; }
        .historial-fecha { font-weight: 700; color: var(--navy); font-size: 0.88rem; }
        .historial-estado { font-size: 0.72rem; text-transform: uppercase; color: #5b6b78; margin-left: 0.5rem; }
        .historial-problema { font-size: 0.88rem; margin-top: 0.2rem; }

        .sin-permiso { background: #f4f4f4; color: #777; border-radius: 8px; padding: 0.9rem 1rem; font-size: 0.85rem; }
    </style>
</head>
<body>
<header>
    <div class="brand">Frío Center · <span>Taller</span></div>
    <nav>
        <a href="{{ route('taller.dashboard') }}">Inicio</a>
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

    <div class="patente-header">
        <span class="patente-chip">{{ $vehiculo->patente }}</span>
        <a href="{{ route('taller.ingreso.index') }}" class="volver">← Buscar otra patente</a>
    </div>

    <div class="card">
        <h2>Vehículo</h2>
        <dt>Marca y modelo</dt>
        <dd>{{ $vehiculo->descripcion ?: '—' }}</dd>
        <dt>Año / Color</dt>
        <dd>{{ $vehiculo->anio ?? '—' }} {{ $vehiculo->color ? '· ' . $vehiculo->color : '' }}</dd>
        <dt>Titular</dt>
        <dd>{{ $vehiculo->cliente->nombre_apellido }} · {{ $vehiculo->cliente->telefono }}</dd>
    </div>

    <div class="card">
        <h2>Ticket de hoy</h2>

        @if ($turnoHoy)
            <span class="estado-badge">{{ str_replace('_', ' ', $turnoHoy->estado) }}</span>

            <dt>Horario reservado</dt>
            <dd>{{ \Carbon\Carbon::parse($turnoHoy->hora_turno)->format('H:i') }} hs</dd>

            <dt>Problemática informada por el cliente</dt>
            <dd>{{ $turnoHoy->problematica }}</dd>

            @if ($turnoHoy->estado === 'reservado')
                <form action="{{ route('taller.ingreso.confirmar', $turnoHoy) }}" method="POST">
                    @csrf
                    <button type="submit" class="confirmar">Confirmar ingreso del vehículo</button>
                </form>
            @elseif ($turnoHoy->estado === 'ingresado')
                <p class="sin-turno" style="margin-top:1rem;">
                    Ingreso ya confirmado a las {{ $turnoHoy->fecha_ingreso_real?->format('H:i') }} hs.
                </p>
                <a href="{{ route('taller.orden-trabajo.edit', $turnoHoy) }}" class="confirmar" style="display:inline-block; text-decoration:none;">
                    {{ $turnoHoy->ordenTrabajo ? 'Editar orden de trabajo' : 'Cargar orden de trabajo' }}
                </a>
            @else
                <p class="sin-turno" style="margin-top:1rem;">Este ticket ya está en una etapa posterior ({{ str_replace('_', ' ', $turnoHoy->estado) }}).</p>
                @if (in_array($turnoHoy->estado, ['presupuestado', 'aceptado']))
                    <a href="{{ route('taller.orden-trabajo.edit', $turnoHoy) }}" class="confirmar" style="display:inline-block; text-decoration:none;">
                        {{ $turnoHoy->ordenTrabajo ? 'Editar orden de trabajo' : 'Cargar orden de trabajo' }}
                    </a>
                @endif
            @endif
        @else
            <p class="sin-turno">No hay ningún turno reservado para hoy con esta patente.</p>
        @endif
    </div>

    <div class="card">
        <h2>Historial de atenciones anteriores</h2>

        @if (!$puedeVerHistorial)
            <div class="sin-permiso">No tenés permiso para ver el historial de este vehículo. Consultá con Administración si lo necesitás.</div>
        @elseif ($historial->isEmpty())
            <p class="sin-turno">No hay atenciones anteriores registradas para esta patente.</p>
        @else
            @foreach ($historial as $item)
                <div class="historial-item">
                    <span class="historial-fecha">{{ $item->fecha_turno->format('d/m/Y') }}</span>
                    <span class="historial-estado">{{ str_replace('_', ' ', $item->estado) }}</span>
                    <div class="historial-problema">{{ $item->problematica }}</div>
                </div>
            @endforeach
        @endif
    </div>
</main>
</body>
</html>
