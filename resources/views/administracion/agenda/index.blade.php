<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de agenda — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 56rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        .link-bloqueos { color: var(--navy); font-size: 0.88rem; font-weight: 600; text-decoration: none; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-top: 1.25rem; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid #c0392b; color: #c0392b; border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 0.5rem 0.4rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; color: #5b6b78; border-bottom: 1px solid var(--line); }
        td { padding: 0.6rem 0.4rem; border-bottom: 1px solid var(--line); vertical-align: middle; }
        .dia-nombre { font-weight: 700; color: var(--navy); font-size: 0.9rem; }
        input[type=time], input[type=number] { width: 100%; padding: 0.4rem 0.5rem; border: 1px solid var(--line); border-radius: 6px; font-size: 0.85rem; }
        input[type=checkbox] { width: 1.1rem; height: 1.1rem; }
        input:focus { outline: 2px solid var(--ice); }
        .col-num { width: 5.5rem; }
        .col-time { width: 6rem; }

        button.guardar { margin-top: 1.25rem; background: var(--navy); color: #fff; border: none; padding: 0.75rem 1.4rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        button.guardar:hover { background: #081a2e; }
        .nota { font-size: 0.8rem; color: #5b6b78; margin-top: 0.75rem; }
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
    <div class="top-row">
        <h1>Configuración de agenda</h1>
        <a href="{{ route('administracion.agenda.bloqueos') }}" class="link-bloqueos">Bloquear días (feriados, vacaciones) →</a>
    </div>

    @if (session('ok'))
        <div class="ok-msg" style="margin-top:1.25rem;">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg" style="margin-top:1.25rem;">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <form action="{{ route('administracion.agenda.update') }}" method="POST">
            @csrf
            <table>
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Activo</th>
                        <th class="col-time">Desde</th>
                        <th class="col-time">Hasta</th>
                        <th class="col-num">Cada (min)</th>
                        <th class="col-num">Turnos por franja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dias as $dia)
                        <tr>
                            <td class="dia-nombre">{{ $dia->nombre }}</td>
                            <td>
                                <input type="checkbox" name="dias[{{ $dia->numero }}][activo]" value="1"
                                    {{ old("dias.{$dia->numero}.activo", $dia->config->activo ?? false) ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="time" name="dias[{{ $dia->numero }}][hora_inicio]"
                                    value="{{ old("dias.{$dia->numero}.hora_inicio", $dia->config?->hora_inicio ? substr($dia->config->hora_inicio, 0, 5) : '08:00') }}">
                            </td>
                            <td>
                                <input type="time" name="dias[{{ $dia->numero }}][hora_fin]"
                                    value="{{ old("dias.{$dia->numero}.hora_fin", $dia->config?->hora_fin ? substr($dia->config->hora_fin, 0, 5) : '12:00') }}">
                            </td>
                            <td>
                                <input type="number" min="5" max="240" name="dias[{{ $dia->numero }}][intervalo_minutos]"
                                    value="{{ old("dias.{$dia->numero}.intervalo_minutos", $dia->config->intervalo_minutos ?? 15) }}">
                            </td>
                            <td>
                                <input type="number" min="1" max="50" name="dias[{{ $dia->numero }}][turnos_por_franja]"
                                    value="{{ old("dias.{$dia->numero}.turnos_por_franja", $dia->config->turnos_por_franja ?? 1) }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="nota">Desmarcá "Activo" en los días que no atienden (por defecto: fines de semana). Los cambios se aplican inmediatamente al formulario público de turnos.</p>

            <button type="submit" class="guardar">Guardar configuración</button>
        </form>
    </div>
</main>
</body>
</html>
