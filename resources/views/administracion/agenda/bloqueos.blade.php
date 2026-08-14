<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloquear días — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 42rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        .volver { color: var(--navy); font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-top: 1.25rem; }
        .card h2 { margin: 0 0 1rem; font-size: 1.05rem; color: var(--navy); }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        .fila-form { display: flex; gap: 0.6rem; align-items: end; flex-wrap: wrap; }
        .campo { display: flex; flex-direction: column; }
        .campo label { font-size: 0.75rem; font-weight: 600; margin-bottom: 0.3rem; }
        input[type=date], input[type=text] { padding: 0.55rem 0.65rem; border: 1px solid var(--line); border-radius: 6px; font-size: 0.88rem; }
        input:focus { outline: 2px solid var(--ice); }
        button.agregar { background: var(--navy); color: #fff; border: none; padding: 0.6rem 1.1rem; border-radius: 6px; font-weight: 700; cursor: pointer; }

        .bloqueo { display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 0; border-top: 1px solid var(--line); }
        .bloqueo:first-child { border-top: none; padding-top: 0; }
        .bloqueo .fechas { font-weight: 700; color: var(--navy); font-size: 0.9rem; }
        .bloqueo .motivo { font-size: 0.85rem; color: #5b6b78; }
        button.borrar { background: none; border: 1px solid var(--error); color: var(--error); padding: 0.35rem 0.8rem; border-radius: 6px; font-size: 0.8rem; cursor: pointer; }
        .vacio { color: #5b6b78; font-size: 0.9rem; }
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
        <h1>Bloquear días</h1>
        <a href="{{ route('administracion.agenda.index') }}" class="volver">← Volver a horarios</a>
    </div>

    @if (session('ok'))
        <div class="ok-msg" style="margin-top:1.25rem;">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg" style="margin-top:1.25rem;">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <h2>Agregar bloqueo</h2>
        <form action="{{ route('administracion.agenda.bloqueos.store') }}" method="POST">
            @csrf
            <div class="fila-form">
                <div class="campo">
                    <label for="fecha_inicio">Desde</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" required>
                </div>
                <div class="campo">
                    <label for="fecha_fin">Hasta</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" required>
                </div>
                <div class="campo" style="flex:1; min-width:10rem;">
                    <label for="motivo">Motivo (opcional)</label>
                    <input type="text" name="motivo" id="motivo" placeholder="Ej. Feriado, vacaciones...">
                </div>
                <button type="submit" class="agregar">Agregar</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Bloqueos cargados</h2>

        @if ($bloqueos->isEmpty())
            <p class="vacio">No hay días bloqueados.</p>
        @else
            @foreach ($bloqueos as $bloqueo)
                <div class="bloqueo">
                    <div>
                        <div class="fechas">
                            {{ $bloqueo->fecha_inicio->format('d/m/Y') }}
                            @if (!$bloqueo->fecha_inicio->equalTo($bloqueo->fecha_fin))
                                → {{ $bloqueo->fecha_fin->format('d/m/Y') }}
                            @endif
                        </div>
                        <div class="motivo">{{ $bloqueo->motivo ?: 'Sin motivo especificado' }}</div>
                    </div>
                    <form action="{{ route('administracion.agenda.bloqueos.destroy', $bloqueo) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar este bloqueo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="borrar">Eliminar</button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
</main>
</body>
</html>
