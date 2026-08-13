<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibir auto — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --error: #c0392b; --ok-green: #2e7d32; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 46rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        h1 { color: var(--navy); font-size: 1.4rem; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .card h2 { margin: 0 0 1rem; font-size: 1.05rem; color: var(--navy); }

        .buscador { display: flex; gap: 0.6rem; }
        .buscador input[type=text] { flex: 1; padding: 0.7rem 0.9rem; border: 1px solid var(--line); border-radius: 8px; font-size: 1.05rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .buscador input:focus { outline: 2px solid var(--ice); }
        .buscador button { background: var(--navy); color: #fff; border: none; padding: 0.7rem 1.3rem; border-radius: 8px; font-weight: 700; cursor: pointer; }

        .resultado { margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid var(--line); }
        .patente-chip { background: var(--navy); color: #fff; font-weight: 800; letter-spacing: 0.08em; padding: 0.4rem 0.9rem; border-radius: 8px; display: inline-block; margin-bottom: 0.75rem; }
        .no-encontrado { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.9rem 1rem; margin-top: 1.25rem; font-size: 0.9rem; }

        table { width: 100%; border-collapse: collapse; margin-top: 0.5rem; }
        th { text-align: left; padding: 0.6rem 0.5rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; color: #5b6b78; border-bottom: 1px solid var(--line); }
        td { padding: 0.65rem 0.5rem; border-bottom: 1px solid var(--line); font-size: 0.9rem; }
        .hora { font-weight: 700; color: var(--navy); }
        button.recibir { background: var(--ok-green); color: #fff; border: none; padding: 0.45rem 0.9rem; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 0.82rem; }
        .vacio { color: #5b6b78; padding: 1rem 0; }

        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
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
    <h1>Recibir auto</h1>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <h2>Recibir por patente</h2>
        <form action="{{ route('administracion.recibir.index') }}" method="GET" class="buscador">
            <input type="text" name="patente" placeholder="AA000AA" value="{{ $patenteBuscada }}" autofocus>
            <button type="submit">Buscar</button>
        </form>

        @if ($noEncontrado)
            <div class="no-encontrado">
                No encontramos un turno para hoy con la patente <strong>{{ $patenteBuscada }}</strong>.
            </div>
        @elseif ($resultadoPatente)
            <div class="resultado">
                <span class="patente-chip">{{ $resultadoPatente->vehiculo->patente }}</span>
                <p style="margin:0 0 0.75rem; font-size:0.9rem;">
                    {{ $resultadoPatente->cliente->nombre_apellido }} · {{ $resultadoPatente->vehiculo->descripcion ?: 'Vehículo' }}
                    · Turno {{ \Carbon\Carbon::parse($resultadoPatente->hora_turno)->format('H:i') }} hs
                </p>

                @if ($resultadoPatente->estado === 'reservado')
                    <form action="{{ route('administracion.recibir.confirmar', $resultadoPatente) }}" method="POST">
                        @csrf
                        <button type="submit" class="recibir">Confirmar recepción</button>
                    </form>
                @else
                    <p style="font-size:0.85rem; color:#5b6b78;">Este vehículo ya fue recibido (estado: {{ str_replace('_', ' ', $resultadoPatente->estado) }}).</p>
                @endif
            </div>
        @endif
    </div>

    <div class="card">
        <h2>Turnos de hoy pendientes de recibir</h2>

        @if ($turnosHoy->isEmpty())
            <p class="vacio">No hay turnos reservados para hoy que falten recibir.</p>
        @else
            <table>
                <thead>
                    <tr><th>Hora</th><th>Patente</th><th>Cliente</th><th>Vehículo</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($turnosHoy as $turno)
                        <tr>
                            <td class="hora">{{ \Carbon\Carbon::parse($turno->hora_turno)->format('H:i') }}</td>
                            <td>{{ $turno->vehiculo->patente }}</td>
                            <td>{{ $turno->cliente->nombre_apellido }}</td>
                            <td>{{ $turno->vehiculo->descripcion ?: '—' }}</td>
                            <td>
                                <form action="{{ route('administracion.recibir.confirmar', $turno) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="recibir">Recibir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</main>
</body>
</html>
