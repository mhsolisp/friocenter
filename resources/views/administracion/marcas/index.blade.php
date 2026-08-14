<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcas y modelos — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 50rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        h1 { color: var(--navy); font-size: 1.4rem; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-top: 1.25rem; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        .fila-form { display: flex; gap: 0.6rem; }
        input[type=text] { flex: 1; padding: 0.6rem 0.75rem; border: 1px solid var(--line); border-radius: 6px; font-size: 0.9rem; }
        input:focus { outline: 2px solid var(--ice); }
        button.agregar { background: var(--navy); color: #fff; border: none; padding: 0.6rem 1.1rem; border-radius: 6px; font-weight: 700; cursor: pointer; white-space: nowrap; }

        .marca-card { border: 1px solid var(--line); border-radius: 10px; padding: 1.1rem 1.25rem; margin-top: 0.9rem; }
        .marca-header { display: flex; justify-content: space-between; align-items: center; }
        .marca-nombre { font-weight: 800; color: var(--navy); font-size: 1.05rem; }
        .marca-count { font-size: 0.78rem; color: #5b6b78; margin-left: 0.4rem; }
        button.borrar-marca { background: none; border: 1px solid var(--error); color: var(--error); padding: 0.3rem 0.7rem; border-radius: 6px; font-size: 0.78rem; cursor: pointer; }

        .modelos-lista { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.85rem; }
        .modelo-chip { display: flex; align-items: center; gap: 0.4rem; background: var(--ice-light); color: var(--navy); padding: 0.3rem 0.4rem 0.3rem 0.7rem; border-radius: 999px; font-size: 0.82rem; font-weight: 600; }
        .modelo-chip button { background: none; border: none; color: var(--navy); cursor: pointer; font-size: 0.9rem; line-height: 1; padding: 0.1rem 0.3rem; }
        .modelo-chip button:hover { color: var(--error); }

        .form-modelo { display: flex; gap: 0.5rem; margin-top: 0.75rem; }
        .form-modelo input { padding: 0.4rem 0.6rem; font-size: 0.82rem; }
        .form-modelo button { background: #fff; border: 1px solid var(--navy); color: var(--navy); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer; }
        .sin-modelos { color: #5b6b78; font-size: 0.82rem; margin-top: 0.75rem; }
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
    <h1>Marcas y modelos</h1>
    <p style="color:#5b6b78; font-size:0.9rem; margin-top:-0.5rem;">Este listado alimenta el selector del formulario público de turnos.</p>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <form action="{{ route('administracion.marcas.store') }}" method="POST" class="fila-form">
            @csrf
            <input type="text" name="nombre" placeholder="Nueva marca, ej. Kia" required>
            <button type="submit" class="agregar">Agregar marca</button>
        </form>
    </div>

    @foreach ($marcas as $marca)
        <div class="marca-card">
            <div class="marca-header">
                <div>
                    <span class="marca-nombre">{{ $marca->nombre }}</span>
                    <span class="marca-count">{{ $marca->modelos_count }} modelo{{ $marca->modelos_count === 1 ? '' : 's' }}</span>
                </div>
                <form action="{{ route('administracion.marcas.destroy', $marca) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar {{ $marca->nombre }} y todos sus modelos? Los vehículos ya cargados con esta marca no se borran, solo quedan sin ese dato.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="borrar-marca">Eliminar marca</button>
                </form>
            </div>

            @if ($marca->modelos->isEmpty())
                <p class="sin-modelos">Todavía no tiene modelos cargados.</p>
            @else
                <div class="modelos-lista">
                    @foreach ($marca->modelos as $modelo)
                        <span class="modelo-chip">
                            {{ $modelo->nombre }}
                            <form action="{{ route('administracion.modelos.destroy', $modelo) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar el modelo {{ $modelo->nombre }}?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Eliminar">✕</button>
                            </form>
                        </span>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('administracion.modelos.store', $marca) }}" method="POST" class="form-modelo">
                @csrf
                <input type="text" name="nombre" placeholder="Nuevo modelo para {{ $marca->nombre }}" required>
                <button type="submit">Agregar</button>
            </form>
        </div>
    @endforeach
</main>
</body>
</html>
