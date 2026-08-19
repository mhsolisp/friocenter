<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuentas corrientes — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 60rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        .filtros { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; gap: 0.9rem; flex-wrap: wrap; align-items: flex-end; }
        .filtros label { display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 0.3rem; color: #5b6b78; }
        .filtros input { padding: 0.5rem 0.7rem; border: 1px solid var(--line); border-radius: 6px; font-size: 0.88rem; }
        .filtros button { background: var(--navy); color: #fff; border: none; padding: 0.55rem 1.1rem; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; }
        .filtros .limpiar { color: var(--navy); font-size: 0.82rem; text-decoration: none; align-self: center; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); font-size: 0.87rem; }
        th { background: var(--navy); color: #fff; text-align: left; padding: 0.65rem 0.9rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; }
        td { padding: 0.7rem 0.9rem; border-top: 1px solid var(--line); vertical-align: middle; }
        tr.fila-cliente { cursor: pointer; }
        tr.fila-cliente:hover { background: var(--ice-light); }
        .monto { text-align: right; font-variant-numeric: tabular-nums; font-weight: 700; }
        .monto.debe { color: var(--error); }
        .monto.favor { color: var(--ok-green); }
        .monto.saldado { color: #888; }
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
        <h1>Cuentas corrientes</h1>
    </div>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('administracion.cuentas-corrientes.index') }}" method="GET" class="filtros">
        <div>
            <label for="buscar">Nombre, DNI o CUIT</label>
            <input type="text" name="buscar" id="buscar" placeholder="Ej. Juan Pérez" value="{{ request('buscar') }}">
        </div>
        <button type="submit">Buscar</button>
        @if (request('buscar'))
            <a href="{{ route('administracion.cuentas-corrientes.index') }}" class="limpiar">Limpiar</a>
        @endif
    </form>

    @if ($clientes->isEmpty())
        <div class="vacio">No hay clientes{{ request('buscar') ? ' con estos filtros' : '' }} todavía.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>DNI / CUIT</th>
                    <th>Teléfono</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    @php
                        $saldo = ($cliente->total_cargo ?? 0) - ($cliente->total_pago ?? 0);
                    @endphp
                    <tr class="fila-cliente" onclick="window.location='{{ route('administracion.cuentas-corrientes.show', $cliente) }}'">
                        <td>{{ $cliente->nombre_apellido }}</td>
                        <td>{{ $cliente->dni ?: $cliente->cuit ?: '—' }}</td>
                        <td>{{ $cliente->telefono ?: '—' }}</td>
                        <td class="monto {{ $saldo > 0 ? 'debe' : ($saldo < 0 ? 'favor' : 'saldado') }}">
                            ${{ number_format(abs($saldo), 2, ',', '.') }}
                            @if ($saldo > 0) (debe) @elseif ($saldo < 0) (a favor) @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($clientes->hasPages())
            <div class="paginacion">
                @if ($clientes->onFirstPage())
                    <span class="disabled">← Anterior</span>
                @else
                    <a href="{{ $clientes->previousPageUrl() }}">← Anterior</a>
                @endif

                @for ($i = 1; $i <= $clientes->lastPage(); $i++)
                    @if ($i === $clientes->currentPage())
                        <span class="active">{{ $i }}</span>
                    @else
                        <a href="{{ $clientes->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor

                @if ($clientes->hasMorePages())
                    <a href="{{ $clientes->nextPageUrl() }}">Siguiente →</a>
                @else
                    <span class="disabled">Siguiente →</span>
                @endif
            </div>
        @endif
    @endif
</main>
</body>
</html>
