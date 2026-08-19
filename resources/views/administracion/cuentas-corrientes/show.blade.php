<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cliente->nombre_apellido }} — Cuenta corriente — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 48rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        h2 { color: var(--navy); font-size: 1.05rem; margin: 1.75rem 0 0.75rem; }
        .volver { color: var(--navy); font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        .saldo-card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .saldo-card .cliente-info p { margin: 0.2rem 0 0; font-size: 0.85rem; color: #5b6b78; }
        .saldo-valor { font-size: 1.6rem; font-weight: 700; font-variant-numeric: tabular-nums; }
        .saldo-valor.debe { color: var(--error); }
        .saldo-valor.favor { color: var(--ok-green); }
        .saldo-valor.saldado { color: #888; }
        .saldo-label { font-size: 0.75rem; color: #5b6b78; text-transform: uppercase; letter-spacing: 0.04em; text-align: right; }

        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; }
        label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; margin-top: 1rem; }
        label:first-of-type { margin-top: 0; }
        input[type=number], textarea, input[type=text] { width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--line); border-radius: 8px; font-size: 0.92rem; font-family: inherit; }
        input:focus, textarea:focus { outline: 2px solid var(--ice); }
        textarea { resize: vertical; min-height: 3.5rem; }
        .fila-2 { display: flex; gap: 0.9rem; }
        .fila-2 > div { flex: 1; }
        .radio-group { display: flex; gap: 1.25rem; margin-top: 0.4rem; }
        .radio-option { display: flex; align-items: center; gap: 0.4rem; font-weight: 500; font-size: 0.9rem; }
        .radio-option input { width: auto; }
        button.guardar { margin-top: 1.5rem; background: var(--navy); color: #fff; border: none; padding: 0.75rem 1.4rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        button.guardar:hover { background: #081a2e; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); font-size: 0.87rem; }
        th { background: var(--navy); color: #fff; text-align: left; padding: 0.65rem 0.9rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; }
        td { padding: 0.7rem 0.9rem; border-top: 1px solid var(--line); vertical-align: middle; }
        .monto { text-align: right; font-variant-numeric: tabular-nums; }
        .monto.cargo { color: var(--error); }
        .monto.pago { color: var(--ok-green); }
        .tipo-badge { font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.55rem; border-radius: 999px; }
        .tipo-badge.cargo { background: #fdecea; color: var(--error); }
        .tipo-badge.pago { background: #eaf7ea; color: var(--ok-green); }
        .vacio { padding: 2rem; text-align: center; color: #5b6b78; background: #fff; border: 1px solid var(--line); border-radius: 10px; }
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
        <h1>{{ $cliente->nombre_apellido }}</h1>
        <a href="{{ route('administracion.cuentas-corrientes.index') }}" class="volver">← Volver a cuentas corrientes</a>
    </div>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <div class="saldo-card">
        <div class="cliente-info">
            <strong>{{ $cliente->dni ? 'DNI '.$cliente->dni : ($cliente->cuit ? 'CUIT '.$cliente->cuit : 'Sin DNI/CUIT cargado') }}</strong>
            <p>{{ $cliente->telefono ?: '—' }} · {{ $cliente->email ?: '—' }}</p>
        </div>
        <div>
            <div class="saldo-label">{{ $saldo > 0 ? 'Debe' : ($saldo < 0 ? 'A favor' : 'Saldo') }}</div>
            <div class="saldo-valor {{ $saldo > 0 ? 'debe' : ($saldo < 0 ? 'favor' : 'saldado') }}">
                ${{ number_format(abs($saldo), 2, ',', '.') }}
            </div>
        </div>
    </div>

    <h2>Nuevo movimiento</h2>
    <div class="card">
        <form action="{{ route('administracion.cuentas-corrientes.store', $cliente) }}" method="POST">
            @csrf

            <label>Tipo</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="tipo" value="cargo" checked> Cargo (queda debiendo)</label>
                <label class="radio-option"><input type="radio" name="tipo" value="pago"> Pago (abona)</label>
            </div>

            <div class="fila-2">
                <div>
                    <label for="monto">Monto</label>
                    <input type="number" step="0.01" min="0.01" name="monto" id="monto" required>
                </div>
                <div>
                    <label for="concepto">Concepto</label>
                    <input type="text" name="concepto" id="concepto" maxlength="150" required placeholder="Ej. Reparación compresor">
                </div>
            </div>

            <label for="observaciones">Observaciones</label>
            <textarea name="observaciones" id="observaciones"></textarea>

            <button type="submit" class="guardar">Registrar movimiento</button>
        </form>
    </div>

    <h2>Movimientos</h2>
    @if ($movimientos->isEmpty())
        <div class="vacio">Todavía no hay movimientos en esta cuenta.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movimientos as $movimiento)
                    <tr>
                        <td>{{ $movimiento->fecha->format('d/m/Y') }}</td>
                        <td><span class="tipo-badge {{ $movimiento->tipo }}">{{ $movimiento->tipo === 'cargo' ? 'Cargo' : 'Pago' }}</span></td>
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
