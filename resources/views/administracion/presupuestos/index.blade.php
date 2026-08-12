<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuestos — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 50rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        h1 { color: var(--navy); font-size: 1.4rem; }
        .fila { display: flex; justify-content: space-between; align-items: center; background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1rem 1.25rem; margin-top: 0.85rem; text-decoration: none; color: inherit; }
        .fila:hover { border-color: var(--ice); }
        .fila .patente { font-weight: 800; color: var(--navy); }
        .fila .info { font-size: 0.85rem; color: #5b6b78; margin-top: 0.15rem; }
        .badge { display: inline-block; padding: 0.22rem 0.65rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .badge.ingresado { background: var(--ice-light); color: var(--navy); }
        .badge.presupuestado { background: #fff4e0; color: #9a6300; }
        .vacio { color: #5b6b78; margin-top: 1.5rem; }
        .sin-orden { color: #b7791f; font-size: 0.78rem; }
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
    <h1>Presupuestos</h1>
    <p style="color:#5b6b78; font-size:0.9rem;">Vehículos ingresados esperando presupuesto, o presupuestados esperando la respuesta del cliente.</p>

    @if ($turnos->isEmpty())
        <p class="vacio">No hay presupuestos pendientes en este momento.</p>
    @else
        @foreach ($turnos as $turno)
            <a href="{{ route('administracion.presupuestos.edit', $turno) }}" class="fila">
                <div>
                    <span class="patente">{{ $turno->vehiculo->patente }}</span>
                    <div class="info">
                        {{ $turno->cliente->nombre_apellido }} · {{ $turno->vehiculo->descripcion ?: 'Vehículo' }}
                        @unless ($turno->ordenTrabajo)
                            · <span class="sin-orden">sin orden de trabajo cargada</span>
                        @endunless
                    </div>
                </div>
                <span class="badge {{ $turno->estado }}">{{ str_replace('_', ' ', $turno->estado) }}</span>
            </a>
        @endforeach
    @endif
</main>
</body>
</html>
