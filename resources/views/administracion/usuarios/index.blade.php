<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --ok-green: #2e7d32; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 54rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        a.nuevo { background: var(--navy); color: #fff; text-decoration: none; padding: 0.6rem 1.1rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem; }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); }
        th { background: var(--navy); color: #fff; text-align: left; padding: 0.7rem 1rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; }
        td { padding: 0.75rem 1rem; border-top: 1px solid var(--line); font-size: 0.88rem; vertical-align: middle; }
        .rol-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; }
        .rol-badge.administracion { background: var(--ice-light); color: var(--navy); }
        .rol-badge.taller { background: #fff4e0; color: #9a6300; }
        .permisos-lista { font-size: 0.78rem; color: #5b6b78; }
        .estado-badge { font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.55rem; border-radius: 999px; }
        .estado-badge.activo { background: #eaf7ea; color: var(--ok-green); }
        .estado-badge.inactivo { background: #f4f4f4; color: #888; }
        .acciones a, .acciones button { font-size: 0.8rem; text-decoration: none; margin-right: 0.6rem; border: none; background: none; cursor: pointer; padding: 0; }
        .acciones a { color: var(--navy); font-weight: 600; }
        .acciones button.desactivar { color: var(--error); font-weight: 600; }
        .acciones button.activar { color: var(--ok-green); font-weight: 600; }
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
        <h1>Usuarios</h1>
        <a href="{{ route('administracion.usuarios.create') }}" class="nuevo">+ Nuevo usuario</a>
    </div>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <table>
        <thead>
            <tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Permisos (Taller)</th><th>Estado</th><th></th></tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td><span class="rol-badge {{ $usuario->rol }}">{{ $usuario->rol }}</span></td>
                    <td class="permisos-lista">
                        @if ($usuario->rol === 'taller')
                            {{ $usuario->permiso_ver_dia ? '✓ Día' : '' }}
                            {{ $usuario->permiso_ver_dias_programados ? ' · ✓ Programados' : '' }}
                            {{ $usuario->permiso_ver_historial ? ' · ✓ Historial' : '' }}
                            @if (!$usuario->permiso_ver_dia && !$usuario->permiso_ver_dias_programados && !$usuario->permiso_ver_historial)
                                Sin permisos asignados
                            @endif
                        @else
                            Acceso total
                        @endif
                    </td>
                    <td><span class="estado-badge {{ $usuario->activo ? 'activo' : 'inactivo' }}">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span></td>
                    <td class="acciones">
                        <a href="{{ route('administracion.usuarios.edit', $usuario) }}">Editar</a>
                        @if ($usuario->id !== auth()->id())
                            <form action="{{ route('administracion.usuarios.toggle', $usuario) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="{{ $usuario->activo ? 'desactivar' : 'activar' }}">
                                    {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>
</body>
</html>
