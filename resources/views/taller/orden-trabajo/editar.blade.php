<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Trabajo — {{ $turno->vehiculo->patente }} — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 38rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .patente-chip { background: var(--navy); color: #fff; font-weight: 800; letter-spacing: 0.08em; padding: 0.5rem 1rem; border-radius: 8px; font-size: 1.2rem; display: inline-block; margin-bottom: 1.25rem; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .card h2 { margin: 0 0 1rem; font-size: 1.05rem; color: var(--navy); }
        dt { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #5b6b78; margin-top: 0.75rem; }
        dt:first-child { margin-top: 0; }
        dd { margin: 0.1rem 0 0; font-weight: 600; }
        label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; margin-top: 1rem; }
        label:first-of-type { margin-top: 0; }
        textarea, input[type=text] { width: 100%; padding: 0.65rem 0.75rem; border: 1px solid var(--line); border-radius: 8px; font-size: 0.92rem; font-family: inherit; }
        textarea { min-height: 6rem; resize: vertical; }
        input:focus, textarea:focus { outline: 2px solid var(--ice); }
        button.guardar { margin-top: 1.25rem; background: var(--navy); color: #fff; border: none; padding: 0.75rem 1.4rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        button.guardar:hover { background: #081a2e; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .nota { font-size: 0.8rem; color: #5b6b78; margin-top: 0.5rem; }
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

    <span class="patente-chip">{{ $turno->vehiculo->patente }}</span>

    <div class="card">
        <h2>Datos del vehículo</h2>
        <dt>Vehículo</dt>
        <dd>{{ $turno->vehiculo->descripcion ?: '—' }}</dd>
        <dt>Cliente</dt>
        <dd>{{ $turno->cliente->nombre_apellido }}</dd>
        <dt>Problemática informada por el cliente</dt>
        <dd>{{ $turno->problematica }}</dd>
    </div>

    <div class="card">
        <h2>Orden de Trabajo</h2>
        <p class="nota">Este documento es interno del Taller: tareas a realizar, repuestos necesarios y tiempo estimado. El presupuesto (monto a cobrar) lo carga Administración por separado.</p>

        <form action="{{ route('taller.orden-trabajo.update', $turno) }}" method="POST">
            @csrf

            <label for="tareas">Tareas a realizar</label>
            <textarea name="tareas" id="tareas" required>{{ old('tareas', $turno->ordenTrabajo->tareas ?? '') }}</textarea>

            <label for="repuestos">Repuestos necesarios</label>
            <textarea name="repuestos" id="repuestos">{{ old('repuestos', $turno->ordenTrabajo->repuestos ?? '') }}</textarea>

            <label for="tiempo_estimado">Tiempo estimado</label>
            <input type="text" name="tiempo_estimado" id="tiempo_estimado" placeholder="Ej. 2 horas, 1 día" value="{{ old('tiempo_estimado', $turno->ordenTrabajo->tiempo_estimado ?? '') }}">

            <button type="submit" class="guardar">Guardar orden de trabajo</button>
        </form>
    </div>
</main>
</body>
</html>
