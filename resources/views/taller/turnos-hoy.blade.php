<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turnos de hoy — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 46rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        h1 { color: var(--navy); font-size: 1.4rem; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); margin-top: 1rem; }
        th { background: var(--navy); color: #fff; text-align: left; padding: 0.7rem 1rem; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; }
        td { padding: 0.75rem 1rem; border-top: 1px solid var(--line); font-size: 0.9rem; }
        .hora { font-weight: 700; color: var(--navy); }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; background: var(--ice-light); color: var(--navy); }
        .vacio { color: #5b6b78; margin-top: 1.5rem; }
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
    <h1>Turnos de hoy — {{ now()->translatedFormat('d \d\e F, Y') }}</h1>

    @if ($turnos->isEmpty())
        <p class="vacio">No hay turnos reservados para hoy.</p>
    @else
        <table>
            <thead>
                <tr><th>Hora</th><th>Patente</th><th>Cliente</th><th>Vehículo</th><th>Estado</th></tr>
            </thead>
            <tbody>
                @foreach ($turnos as $turno)
                    <tr>
                        <td class="hora">{{ \Carbon\Carbon::parse($turno->hora_turno)->format('H:i') }}</td>
                        <td>{{ $turno->vehiculo->patente }}</td>
                        <td>{{ $turno->cliente->nombre_apellido }}</td>
                        <td>{{ $turno->vehiculo->descripcion ?: '—' }}</td>
                        <td><span class="badge">{{ str_replace('_', ' ', $turno->estado) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</main>
</body>
</html>
