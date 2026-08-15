<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de presupuestos — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; --warn: #9a6300; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 72rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        .volver { color: var(--navy); font-size: 0.85rem; font-weight: 600; text-decoration: none; }

        .filtros { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; gap: 0.9rem; flex-wrap: wrap; align-items: flex-end; }
        .filtros label { display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 0.3rem; color: #5b6b78; }
        .filtros input, .filtros select { padding: 0.5rem 0.7rem; border: 1px solid var(--line); border-radius: 6px; font-size: 0.88rem; }
        .filtros button { background: var(--navy); color: #fff; border: none; padding: 0.55rem 1.1rem; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; }
        .filtros .limpiar { color: var(--navy); font-size: 0.82rem; text-decoration: none; align-self: center; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); font-size: 0.85rem; }
        th { background: var(--navy); color: #fff; text-align: left; padding: 0.65rem 0.9rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; }
        td { padding: 0.65rem 0.9rem; border-top: 1px solid var(--line); vertical-align: middle; white-space: nowrap; }
        tbody tr { cursor: pointer; }
        tbody tr:hover { background: var(--ice-light); }
        .numero { font-weight: 700; color: var(--navy); font-variant-numeric: tabular-nums; }
        .monto { font-weight: 700; text-align: right; font-variant-numeric: tabular-nums; }
        .estado-badge { font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 999px; text-transform: uppercase; }
        .estado-badge.pendiente { background: #fff4e0; color: var(--warn); }
        .estado-badge.aceptado { background: #eaf7ea; color: var(--ok-green); }
        .estado-badge.rechazado { background: #fdecea; color: var(--error); }
        .col-acciones a { color: var(--navy); font-size: 0.8rem; font-weight: 600; text-decoration: none; }
        .col-acciones a:hover { text-decoration: underline; }
        .vacio { padding: 2.5rem; text-align: center; color: #5b6b78; background: #fff; border: 1px solid var(--line); border-radius: 10px; }

        .paginacion { margin-top: 1.25rem; display: flex; justify-content: center; gap: 0.4rem; }
        .paginacion a, .paginacion span { padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.85rem; text-decoration: none; color: var(--navy); border: 1px solid var(--line); background: #fff; }
        .paginacion .active { background: var(--navy); color: #fff; border-color: var(--navy); }
        .paginacion .disabled { color: #b7c2c9; }
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
        <h1>Historial de presupuestos</h1>
        <a href="{{ route('administracion.presupuestos.index') }}" class="volver">← Volver a la cola</a>
    </div>

    <form action="{{ route('administracion.presupuestos.historial') }}" method="GET" class="filtros">
        <div>
            <label for="buscar">Cliente o patente</label>
            <input type="text" name="buscar" id="buscar" placeholder="Ej. García o AB123CD" value="{{ request('buscar') }}">
        </div>
        <div>
            <label for="estado">Estado</label>
            <select name="estado" id="estado">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="aceptado" {{ request('estado') === 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
            </select>
        </div>
        <button type="submit">Filtrar</button>
        @if (request('buscar') || request('estado'))
            <a href="{{ route('administracion.presupuestos.historial') }}" class="limpiar">Limpiar filtros</a>
        @endif
    </form>

    @if ($presupuestos->isEmpty())
        <div class="vacio">No hay presupuestos cargados{{ request('buscar') || request('estado') ? ' con estos filtros' : '' }} todavía.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>N° Presupuesto</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Patente</th>
                    <th style="text-align:right;">Monto</th>
                    <th>Estado</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presupuestos as $presupuesto)
                    <tr onclick="window.location='{{ route('administracion.presupuestos.edit', $presupuesto->turno) }}'">
                        <td class="numero">{{ $presupuesto->numero_completo ?? '—' }}</td>
                        <td>{{ $presupuesto->fecha_envio?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $presupuesto->turno->cliente->nombre_apellido }}</td>
                        <td>{{ $presupuesto->turno->vehiculo->descripcion ?: '—' }}</td>
                        <td>{{ $presupuesto->turno->vehiculo->patente }}</td>
                        <td class="monto">${{ number_format($presupuesto->monto, 2, ',', '.') }}</td>
                        <td><span class="estado-badge {{ $presupuesto->estado }}">{{ $presupuesto->estado }}</span></td>
                        <td class="col-acciones" onclick="event.stopPropagation();">
                            <a href="{{ route('administracion.presupuestos.pdf', $presupuesto->turno) }}" target="_blank">Descargar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($presupuestos->hasPages())
            <div class="paginacion">
                @if ($presupuestos->onFirstPage())
                    <span class="disabled">← Anterior</span>
                @else
                    <a href="{{ $presupuestos->previousPageUrl() }}">← Anterior</a>
                @endif

                @for ($i = 1; $i <= $presupuestos->lastPage(); $i++)
                    @if ($i === $presupuestos->currentPage())
                        <span class="active">{{ $i }}</span>
                    @else
                        <a href="{{ $presupuestos->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor

                @if ($presupuestos->hasMorePages())
                    <a href="{{ $presupuestos->nextPageUrl() }}">Siguiente →</a>
                @else
                    <span class="disabled">Siguiente →</span>
                @endif
            </div>
        @endif
    @endif
</main>
</body>
</html>
