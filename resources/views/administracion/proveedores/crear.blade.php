<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo proveedor — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 34rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        .volver { color: var(--navy); font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; margin-top: 1rem; }
        label:first-of-type { margin-top: 0; }
        input[type=text], input[type=email], select, textarea { width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--line); border-radius: 8px; font-size: 0.92rem; font-family: inherit; }
        input:focus, select:focus, textarea:focus { outline: 2px solid var(--ice); }
        textarea { resize: vertical; min-height: 4.5rem; }
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
        <h1>Nuevo proveedor</h1>
        <a href="{{ route('administracion.proveedores.index') }}" class="volver">← Volver</a>
    </div>

    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <form action="{{ route('administracion.proveedores.store') }}" method="POST">
            @csrf

            <label for="razon_social">Razón social</label>
            <input type="text" name="razon_social" id="razon_social" required value="{{ old('razon_social') }}">

            <div class="fila-2">
                <div>
                    <label for="cuit">CUIT</label>
                    <input type="text" name="cuit" id="cuit" required placeholder="30-70987654-2" value="{{ old('cuit') }}">
                </div>
                <div>
                    <label for="rubro_id">Rubro</label>
                    <select name="rubro_id" id="rubro_id">
                        <option value="">Sin especificar</option>
                        @foreach ($rubros as $rubro)
                            <option value="{{ $rubro->id }}" {{ (string) old('rubro_id') === (string) $rubro->id ? 'selected' : '' }}>{{ $rubro->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label>Condición fiscal</label>
            <div class="radio-group">
                <label class="radio-option">
                    <input type="radio" name="condicion_fiscal" value="consumidor_final" {{ old('condicion_fiscal') === 'consumidor_final' ? 'checked' : '' }}> Consumidor final
                </label>
                <label class="radio-option">
                    <input type="radio" name="condicion_fiscal" value="factura" {{ old('condicion_fiscal') === 'factura' ? 'checked' : '' }}> Factura
                </label>
            </div>

            <div class="fila-2">
                <div>
                    <label for="telefono">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}">
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}">
                </div>
            </div>

            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}">

            <label for="observaciones">Observaciones</label>
            <textarea name="observaciones" id="observaciones">{{ old('observaciones') }}</textarea>

            <button type="submit" class="guardar">Crear proveedor</button>
        </form>
    </div>
</main>
</body>
</html>
