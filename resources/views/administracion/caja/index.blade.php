<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 56rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        h2 { color: var(--navy); font-size: 1.05rem; margin: 1.75rem 0 0.75rem; }
        .metodos-link { color: var(--navy); font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .aviso { background: #fff8e6; border: 1px solid #e0b03c; color: #7a5a00; border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        .filtros { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; gap: 0.9rem; flex-wrap: wrap; align-items: flex-end; }
        .filtros label { display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 0.3rem; color: #5b6b78; }
        .filtros input, .filtros select { padding: 0.5rem 0.7rem; border: 1px solid var(--line); border-radius: 6px; font-size: 0.88rem; }
        .filtros button { background: var(--navy); color: #fff; border: none; padding: 0.55rem 1.1rem; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; }
        .filtros .limpiar { color: var(--navy); font-size: 0.82rem; text-decoration: none; align-self: center; }

        .estado-dia { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; font-size: 0.92rem; }
        .estado-dia.cerrada { background: #f4f4f4; color: #555; }
        button.cerrar-btn { background: var(--navy); color: #fff; border: none; padding: 0.55rem 1.1rem; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); font-size: 0.87rem; }
        th { background: var(--navy); color: #fff; text-align: left; padding: 0.6rem 0.9rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; }
        td { padding: 0.65rem 0.9rem; border-top: 1px solid var(--line); vertical-align: middle; }
        tfoot td { border-top: 2px solid var(--navy); font-weight: 700; }
        .monto { text-align: right; font-variant-numeric: tabular-nums; }
        .monto.ingreso { color: var(--ok-green); }
        .monto.egreso { color: var(--error); }
        .tipo-badge { font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.55rem; border-radius: 999px; }
        .tipo-badge.ingreso { background: #eaf7ea; color: var(--ok-green); }
        .tipo-badge.egreso { background: #fdecea; color: var(--error); }
        .vacio { padding: 2rem; text-align: center; color: #5b6b78; background: #fff; border: 1px solid var(--line); border-radius: 10px; }

        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; }
        label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; margin-top: 1rem; }
        label:first-of-type { margin-top: 0; }
        input[type=text], input[type=number], select, textarea { width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--line); border-radius: 8px; font-size: 0.92rem; font-family: inherit; }
        input:focus, select:focus, textarea:focus { outline: 2px solid var(--ice); }
        textarea { resize: vertical; min-height: 3.5rem; }
        .fila-2 { display: flex; gap: 0.9rem; }
        .fila-2 > div { flex: 1; }
        .radio-group { display: flex; gap: 1.25rem; margin-top: 0.4rem; }
        .radio-option { display: flex; align-items: center; gap: 0.4rem; font-weight: 500; font-size: 0.9rem; }
        .radio-option input { width: auto; }
        button.guardar { margin-top: 1.5rem; background: var(--navy); color: #fff; border: none; padding: 0.75rem 1.4rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        button.guardar:hover { background: #081a2e; }
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
        <h1>Caja</h1>
        <a href="{{ route('administracion.caja.metodos.index') }}" class="metodos-link">Métodos de pago</a>
    </div>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('administracion.caja.index') }}" method="GET" class="filtros">
        <div>
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ $fecha->toDateString() }}">
        </div>
        <button type="submit">Ver</button>
        @if ($fecha->toDateString() !== today()->toDateString())
            <a href="{{ route('administracion.caja.index') }}" class="limpiar">Volver a hoy</a>
        @endif
    </form>

    <div class="estado-dia {{ $cierre ? 'cerrada' : '' }}">
        @if ($cierre)
            <span>🔒 Caja del {{ $fecha->format('d/m/Y') }} cerrada por {{ $cierre->usuario->name }} el {{ $cierre->created_at->format('d/m/Y H:i') }}hs.</span>
        @else
            <span>Caja del {{ $fecha->format('d/m/Y') }} abierta.</span>
            <form action="{{ route('administracion.caja.cerrar') }}" method="POST"
                  onsubmit="return confirm('¿Cerrar la caja del {{ $fecha->format('d/m/Y') }}? Vas a poder seguir cargando movimientos igual, pero van a pedir doble confirmación.');">
                @csrf
                <input type="hidden" name="fecha" value="{{ $fecha->toDateString() }}">
                <button type="submit" class="cerrar-btn">Cerrar caja</button>
            </form>
        @endif
    </div>

    <h2>Resumen por método de pago</h2>
    @if ($totalesPorMetodo->isEmpty())
        <div class="vacio">Sin movimientos este día.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Método</th>
                    <th>Ingresos</th>
                    <th>Egresos</th>
                    <th>Neto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($totalesPorMetodo as $t)
                    <tr>
                        <td>{{ $t['metodo']->nombre }}</td>
                        <td class="monto ingreso">${{ number_format($t['ingresos'], 2, ',', '.') }}</td>
                        <td class="monto egreso">${{ number_format($t['egresos'], 2, ',', '.') }}</td>
                        <td class="monto">${{ number_format($t['ingresos'] - $t['egresos'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td class="monto ingreso">${{ number_format($totalIngresos, 2, ',', '.') }}</td>
                    <td class="monto egreso">${{ number_format($totalEgresos, 2, ',', '.') }}</td>
                    <td class="monto">${{ number_format($totalIngresos - $totalEgresos, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <h2>Nuevo movimiento</h2>
    @if ($metodos->isEmpty())
        <div class="aviso">
            Todavía no hay métodos de pago cargados. <a href="{{ route('administracion.caja.metodos.index') }}">Agregá uno</a> para poder registrar movimientos.
        </div>
    @else
        <div class="card">
            <form action="{{ route('administracion.caja.store') }}" method="POST" id="form-movimiento" onsubmit="return confirmarSiCerrada();">
                @csrf
                <input type="hidden" name="fecha" value="{{ $fecha->toDateString() }}">

                <label>Tipo</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="tipo" value="ingreso" checked> Ingreso</label>
                    <label class="radio-option"><input type="radio" name="tipo" value="egreso"> Egreso</label>
                </div>

                <div class="fila-2">
                    <div>
                        <label for="metodo_pago_id">Método de pago</label>
                        <select name="metodo_pago_id" id="metodo_pago_id" required>
                            <option value="">Elegir…</option>
                            @foreach ($metodos as $metodo)
                                <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="monto">Monto</label>
                        <input type="number" step="0.01" min="0.01" name="monto" id="monto" required>
                    </div>
                </div>

                <label for="concepto">Concepto</label>
                <input type="text" name="concepto" id="concepto" maxlength="150" required placeholder="Ej. Pago presupuesto #924825-0012">

                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones"></textarea>

                <button type="submit" class="guardar">Registrar movimiento</button>
            </form>
        </div>

        <script>
            function confirmarSiCerrada() {
                @if ($cierre)
                    if (!confirm('¡Atención! La caja del {{ $fecha->format('d/m/Y') }} ya está cerrada.\n¿Querés cargar este movimiento igual?')) {
                        return false;
                    }
                    return confirm('Confirmá una vez más: se va a agregar un movimiento a un día ya cerrado.');
                @else
                    return true;
                @endif
            }
        </script>
    @endif

    <h2>Movimientos del día</h2>
    @if ($movimientos->isEmpty())
        <div class="vacio">Sin movimientos este día.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Tipo</th>
                    <th>Método</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movimientos as $movimiento)
                    <tr>
                        <td>{{ $movimiento->created_at->format('H:i') }}</td>
                        <td><span class="tipo-badge {{ $movimiento->tipo }}">{{ ucfirst($movimiento->tipo) }}</span></td>
                        <td>{{ $movimiento->metodoPago->nombre }}</td>
                        <td>{{ $movimiento->concepto }}</td>
                        <td class="monto {{ $movimiento->tipo }}">${{ number_format($movimiento->monto, 2, ',', '.') }}</td>
                        <td>{{ $movimiento->usuario->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</main>
</body>
</html>
