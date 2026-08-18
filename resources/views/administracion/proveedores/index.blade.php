<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
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
        .acciones-top { display: flex; gap: 0.75rem; align-items: center; }
        .rubros-link { color: var(--navy); font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        a.nuevo { background: var(--navy); color: #fff; text-decoration: none; padding: 0.6rem 1.1rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        .filtros { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; gap: 0.9rem; flex-wrap: wrap; align-items: flex-end; }
        .filtros label { display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 0.3rem; color: #5b6b78; }
        .filtros input, .filtros select { padding: 0.5rem 0.7rem; border: 1px solid var(--line); border-radius: 6px; font-size: 0.88rem; }
        .filtros button { background: var(--navy); color: #fff; border: none; padding: 0.55rem 1.1rem; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; }
        .filtros .limpiar { color: var(--navy); font-size: 0.82rem; text-decoration: none; align-self: center; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); font-size: 0.87rem; }
        th { background: var(--navy); color: #fff; text-align: left; padding: 0.65rem 0.9rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; }
        td { padding: 0.7rem 0.9rem; border-top: 1px solid var(--line); vertical-align: middle; }
        .rubro-badge { display: inline-block; background: var(--ice-light); color: var(--navy); font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 999px; }
        .estado-badge { font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.55rem; border-radius: 999px; }
        .estado-badge.activo { background: #eaf7ea; color: var(--ok-green); }
        .estado-badge.inactivo { background: #f4f4f4; color: #888; }
        .acciones a, .acciones button { font-size: 0.8rem; text-decoration: none; margin-right: 0.6rem; border: none; background: none; cursor: pointer; padding: 0; }
        .acciones a { color: var(--navy); font-weight: 600; }
        .acciones button.desactivar { color: var(--error); font-weight: 600; }
        .acciones button.activar { color: var(--ok-green); font-weight: 600; }
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
        <h1>Proveedores</h1>
        <div class="acciones-top">
            <a href="{{ route('administracion.proveedores.rubros.index') }}" class="rubros-link">Gestionar rubros</a>
            <a href="{{ route('administracion.proveedores.create') }}" class="nuevo">+ Nuevo proveedor</a>
        </div>
    </div>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('administracion.proveedores.index') }}" method="GET" class="filtros">
        <div>
            <label for="buscar">Razón social o CUIT</label>
            <input type="text" name="buscar" id="buscar" placeholder="Ej. Refrigerantes del Litoral" value="{{ request('buscar') }}">
        </div>
        <div>
            <label for="rubro_id">Rubro</label>
            <select name="rubro_id" id="rubro_id">
                <option value="">Todos</option>
                @foreach ($rubros as $rubro)
                    <option value="{{ $rubro->id }}" {{ (string) request('rubro_id') === (string) $rubro->id ? 'selected' : '' }}>{{ $rubro->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="estado">Estado</label>
            <select name="estado" id="estado">
                <option value="activos" {{ request('estado', 'activos') === 'activos' ? 'selected' : '' }}>Activos</option>
                <option value="inactivos" {{ request('estado') === 'inactivos' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <button type="submit">Filtrar</button>
        @if (request('buscar') || request('rubro_id') || request('estado') === 'inactivos')
            <a href="{{ route('administracion.proveedores.index') }}" class="limpiar">Limpiar filtros</a>
        @endif
    </form>

    @if ($proveedores->isEmpty())
        <div class="vacio">No hay proveedores{{ request('buscar') || request('rubro_id') ? ' con estos filtros' : '' }} todavía.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Razón social</th>
                    <th>CUIT</th>
                    <th>Rubro</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proveedores as $proveedor)
                    <tr>
                        <td>{{ $proveedor->razon_social }}</td>
                        <td>{{ $proveedor->cuit }}</td>
                        <td>
                            @if ($proveedor->rubro)
                                <span class="rubro-badge">{{ $proveedor->rubro->nombre }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $proveedor->telefono ?: '—' }}</td>
                        <td>{{ $proveedor->email ?: '—' }}</td>
                        <td><span class="estado-badge {{ $proveedor->activo ? 'activo' : 'inactivo' }}">{{ $proveedor->activo ? 'Activo' : 'Inactivo' }}</span></td>
                        <td class="acciones">
                            <a href="{{ route('administracion.proveedores.edit', $proveedor) }}">Editar</a>
                            <form action="{{ route('administracion.proveedores.toggle', $proveedor) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="{{ $proveedor->activo ? 'desactivar' : 'activar' }}">
                                    {{ $proveedor->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($proveedores->hasPages())
            <div class="paginacion">
                @if ($proveedores->onFirstPage())
                    <span class="disabled">← Anterior</span>
                @else
                    <a href="{{ $proveedores->previousPageUrl() }}">← Anterior</a>
                @endif

                @for ($i = 1; $i <= $proveedores->lastPage(); $i++)
                    @if ($i === $proveedores->currentPage())
                        <span class="active">{{ $i }}</span>
                    @else
                        <a href="{{ $proveedores->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor

                @if ($proveedores->hasMorePages())
                    <a href="{{ $proveedores->nextPageUrl() }}">Siguiente →</a>
                @else
                    <span class="disabled">Siguiente →</span>
                @endif
            </div>
        @endif
    @endif
</main>
</body>
</html>
