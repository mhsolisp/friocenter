<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --frost: #f5f8fa; --line: #dde6ea; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 60rem; margin: 2rem auto; padding: 0 1.5rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-top: 1.5rem; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.25rem; }
        .card.disabled { opacity: 0.55; }
        .card h3 { margin: 0 0 0.4rem; color: var(--navy); font-size: 1rem; }
        .card p { margin: 0; font-size: 0.85rem; color: #5b6b78; }
        .badge { display: inline-block; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 0.15rem 0.6rem; border-radius: 999px; margin-top: 0.6rem; }
        .badge.ok { background: #eaf6fa; color: var(--navy); }
        .badge.no { background: #f1f1f1; color: #888; }
    </style>
</head>
<body>
<header>
    <div class="brand">Frío Center · <span>Taller</span></div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Salir ({{ auth()->user()->name }})</button>
    </form>
</header>
<main>
    <h1 style="color: var(--navy);">Hola, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
    <p style="color:#5b6b78;">Tus accesos según los permisos que te asignó Administración:</p>

    <div class="grid">
        <div class="card {{ auth()->user()->tienePermiso('ver_dia') ? '' : 'disabled' }}">
            <h3>Turnos del día</h3>
            <p>Ver los ingresos programados para hoy.</p>
            <span class="badge {{ auth()->user()->tienePermiso('ver_dia') ? 'ok' : 'no' }}">
                {{ auth()->user()->tienePermiso('ver_dia') ? 'Habilitado' : 'Sin acceso' }}
            </span>
        </div>
        <div class="card {{ auth()->user()->tienePermiso('ver_dias_programados') ? '' : 'disabled' }}">
            <h3>Días programados</h3>
            <p>Ver los turnos agendados a futuro.</p>
            <span class="badge {{ auth()->user()->tienePermiso('ver_dias_programados') ? 'ok' : 'no' }}">
                {{ auth()->user()->tienePermiso('ver_dias_programados') ? 'Habilitado' : 'Sin acceso' }}
            </span>
        </div>
        <div class="card {{ auth()->user()->tienePermiso('ver_historial') ? '' : 'disabled' }}">
            <h3>Búsqueda por patente + historial</h3>
            <p>Buscar un vehículo y ver atenciones anteriores.</p>
            <span class="badge {{ auth()->user()->tienePermiso('ver_historial') ? 'ok' : 'no' }}">
                {{ auth()->user()->tienePermiso('ver_historial') ? 'Habilitado' : 'Sin acceso' }}
            </span>
        </div>
    </div>

    <p style="color:#888; font-size:0.85rem; margin-top:2rem;">Estas secciones todavía son placeholders — se activan con la funcionalidad real en la próxima etapa.</p>
</main>
</body>
</html>
