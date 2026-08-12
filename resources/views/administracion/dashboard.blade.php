<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración — Frío Center</title>
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
        .card h3 { margin: 0 0 0.4rem; color: var(--navy); font-size: 1rem; }
        .card p { margin: 0; font-size: 0.85rem; color: #5b6b78; }
        .badge { display: inline-block; background: #eaf6fa; color: var(--navy); font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 0.15rem 0.6rem; border-radius: 999px; margin-top: 0.6rem; }
    </style>
</head>
<body>
<header>
    <div class="brand">Frío Center · <span>Administración</span></div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Salir ({{ auth()->user()->name }})</button>
    </form>
</header>
<main>
    <h1 style="color: var(--navy);">Hola, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
    <p style="color:#5b6b78;">Este panel se va a ir completando con cada módulo que armemos. Como Administración, vas a tener acceso a todo lo que ve el rol Taller también.</p>

    <div class="grid">
        <div class="card">
            <h3>Configuración de agenda</h3>
            <p>Horarios, cantidad de turnos y bloqueo de días.</p>
            <span class="badge">Próxima etapa</span>
        </div>
        <div class="card">
            <h3>Marcas y modelos</h3>
            <p>Administrar el listado del selector de vehículos.</p>
            <span class="badge">Próxima etapa</span>
        </div>
        <div class="card">
            <h3>Presupuestos</h3>
            <p>Cargar y enviar presupuestos a clientes.</p>
            <span class="badge">Próxima etapa</span>
        </div>
        <div class="card">
            <h3>Usuarios</h3>
            <p>Gestionar accesos y permisos del equipo de Taller.</p>
            <span class="badge">Próxima etapa</span>
        </div>
    </div>
</main>
</body>
</html>
