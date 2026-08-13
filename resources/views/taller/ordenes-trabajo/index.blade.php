<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes de trabajo — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 50rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin-bottom: 0.25rem; }
        h2.seccion { color: var(--navy); font-size: 1rem; margin: 2rem 0 0.75rem; }
        .fila { display: flex; justify-content: space-between; align-items: center; background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1rem 1.25rem; margin-top: 0.75rem; }
        .fila .patente { font-weight: 800; color: var(--navy); }
        .fila .info { font-size: 0.85rem; color: #5b6b78; margin-top: 0.15rem; }
        .fila .tomada-por { font-size: 0.8rem; color: var(--navy); font-weight: 600; }
        button.tomar { background: var(--ice); color: #fff; border: none; padding: 0.55rem 1.1rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        a.continuar { background: var(--navy); color: #fff; text-decoration: none; padding: 0.55rem 1.1rem; border-radius: 8px; font-weight: 700; }
        .vacio { color: #5b6b78; margin-top: 0.75rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
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
    <h1>Órdenes de trabajo</h1>
    <p style="color:#5b6b78; font-size:0.9rem;">Vehículos que Administración ya recibió y están esperando diagnóstico.</p>

    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <h2 class="seccion">Pendientes de tomar</h2>
    @if ($pendientes->isEmpty())
        <p class="vacio">No hay órdenes pendientes en este momento.</p>
    @else
        @foreach ($pendientes as $orden)
            <div class="fila">
                <div>
                    <span class="patente">{{ $orden->turno->vehiculo->patente }}</span>
                    <div class="info">{{ $orden->turno->cliente->nombre_apellido }} · {{ $orden->turno->vehiculo->descripcion ?: 'Vehículo' }}</div>
                    <div class="info">{{ $orden->turno->problematica }}</div>
                </div>
                <form action="{{ route('taller.ordenes-trabajo.tomar', $orden) }}" method="POST">
                    @csrf
                    <button type="submit" class="tomar">Tomar</button>
                </form>
            </div>
        @endforeach
    @endif

    <h2 class="seccion">En curso</h2>
    @if ($enCurso->isEmpty())
        <p class="vacio">No hay órdenes en curso.</p>
    @else
        @foreach ($enCurso as $orden)
            <div class="fila">
                <div>
                    <span class="patente">{{ $orden->turno->vehiculo->patente }}</span>
                    <div class="info">{{ $orden->turno->cliente->nombre_apellido }} · {{ $orden->turno->vehiculo->descripcion ?: 'Vehículo' }}</div>
                    <div class="tomada-por">Tomada por {{ $orden->usuario->name }}{{ $orden->tareas ? ' · detalle cargado' : ' · falta cargar el detalle' }}</div>
                </div>
                <a href="{{ route('taller.orden-trabajo.edit', $orden->turno) }}" class="continuar">
                    {{ $orden->tareas ? 'Ver / editar' : 'Cargar detalle' }}
                </a>
            </div>
        @endforeach
    @endif
</main>
</body>
</html>
