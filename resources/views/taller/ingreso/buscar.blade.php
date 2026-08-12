<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar por patente — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 34rem; margin: 3rem auto; padding: 0 1.5rem; }
        h1 { color: var(--navy); font-size: 1.4rem; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.75rem; margin-top: 1.5rem; }
        label { display:block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem; }
        input[type=text] {
            width: 100%; padding: 0.85rem 1rem; border: 1px solid var(--line); border-radius: 8px;
            font-size: 1.3rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;
        }
        input:focus { outline: 2px solid var(--ice); }
        button.buscar { width: 100%; margin-top: 1rem; background: var(--navy); color: #fff; border: none; padding: 0.8rem; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; }
        button.buscar:hover { background: #081a2e; }
        .no-encontrado { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.9rem 1rem; margin-top: 1.25rem; font-size: 0.9rem; }
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
    <h1>Buscar vehículo por patente</h1>

    <div class="card">
        <form action="{{ route('taller.ingreso.index') }}" method="GET">
            <label for="patente">Patente</label>
            <input type="text" name="patente" id="patente" maxlength="10" autofocus placeholder="AA000AA" value="{{ $patenteBuscada ?? '' }}">
            <button type="submit" class="buscar">Buscar</button>
        </form>

        @isset($noEncontrado)
            <div class="no-encontrado">
                No encontramos ningún vehículo registrado con la patente <strong>{{ $patenteBuscada }}</strong>.
            </div>
        @endisset
    </div>
</main>
</body>
</html>
