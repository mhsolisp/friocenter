<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métodos de pago — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 40rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        .volver { color: var(--navy); font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        .fila-form { display: flex; gap: 0.6rem; }
        input[type=text] { flex: 1; padding: 0.6rem 0.75rem; border: 1px solid var(--line); border-radius: 6px; font-size: 0.9rem; }
        input:focus { outline: 2px solid var(--ice); }
        button.agregar { background: var(--navy); color: #fff; border: none; padding: 0.6rem 1.1rem; border-radius: 6px; font-weight: 700; cursor: pointer; white-space: nowrap; }

        .metodo-fila { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-top: 1px solid var(--line); }
        .metodo-fila:first-of-type { border-top: none; }
        .metodo-nombre { font-weight: 700; color: var(--navy); }
        .metodo-count { font-size: 0.78rem; color: #5b6b78; margin-left: 0.4rem; font-weight: 400; }
        button.borrar { background: none; border: 1px solid var(--error); color: var(--error); padding: 0.3rem 0.7rem; border-radius: 6px; font-size: 0.78rem; cursor: pointer; }
        .sin-metodos { color: #5b6b78; font-size: 0.88rem; padding: 0.5rem 0; }
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
        <h1>Métodos de pago</h1>
        <a href="{{ route('administracion.caja.index') }}" class="volver">← Volver a caja</a>
    </div>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <form action="{{ route('administracion.caja.metodos.store') }}" method="POST" class="fila-form" style="margin-bottom: 1rem;">
            @csrf
            <input type="text" name="nombre" placeholder="Nuevo método, ej. Transferencia" required>
            <button type="submit" class="agregar">Agregar método</button>
        </form>

        @if ($metodos->isEmpty())
            <p class="sin-metodos">Todavía no hay métodos de pago cargados.</p>
        @else
            @foreach ($metodos as $metodo)
                <div class="metodo-fila">
                    <span class="metodo-nombre">{{ $metodo->nombre }}<span class="metodo-count">{{ $metodo->movimientos_count }} movimiento{{ $metodo->movimientos_count === 1 ? '' : 's' }}</span></span>
                    <form action="{{ route('administracion.caja.metodos.destroy', $metodo) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar el método {{ $metodo->nombre }}?');">
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
