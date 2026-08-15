<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de correo — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --error: #c0392b; --ok-green: #2e7d32; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header .brand { font-weight: 700; letter-spacing: 0.08em; }
        header .brand span { color: var(--ice); }
        header nav a { color: #cfe3ea; text-decoration: none; font-size: 0.85rem; margin-right: 1rem; }
        header form button { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 0.4rem 0.9rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        main { max-width: 36rem; margin: 2rem auto 3rem; padding: 0 1.5rem; }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        h1 { color: var(--navy); font-size: 1.4rem; margin: 0; }
        .volver { color: var(--navy); font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .card h2 { margin: 0 0 1rem; font-size: 1.05rem; color: var(--navy); }
        .ok-msg { background: #eaf7ea; border: 1px solid var(--ok-green); color: var(--ok-green); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .error-msg { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem; margin-top: 1rem; }
        label:first-of-type { margin-top: 0; }
        input[type=text], input[type=number], input[type=password], input[type=email], select { width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--line); border-radius: 8px; font-size: 0.9rem; }
        input:focus, select:focus { outline: 2px solid var(--ice); }
        .row { display: grid; grid-template-columns: 2fr 1fr; gap: 0.9rem; }
        .nota { font-size: 0.78rem; color: #5b6b78; margin-top: 0.4rem; }
        button.guardar { margin-top: 1.4rem; background: var(--navy); color: #fff; border: none; padding: 0.75rem 1.4rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
        button.guardar:hover { background: #081a2e; }
        .estado { display: inline-block; padding: 0.25rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem; }
        .estado.ok { background: #eaf7ea; color: var(--ok-green); }
        .estado.pendiente { background: #fff4e0; color: #9a6300; }
        .fila-prueba { display: flex; gap: 0.6rem; }
        .fila-prueba input { flex: 1; }
        button.probar { background: #fff; border: 1px solid var(--navy); color: var(--navy); padding: 0.6rem 1.1rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
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
        <h1>Configuración de correo</h1>
        <a href="{{ route('administracion.dashboard') }}" class="volver">← Volver</a>
    </div>

    @if (session('ok'))
        <div class="ok-msg">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <span class="estado {{ $configuracion->estaConfigurado() ? 'ok' : 'pendiente' }}">
            {{ $configuracion->estaConfigurado() ? 'Configurado' : 'Sin configurar' }}
        </span>
        <h2>Datos del servidor SMTP</h2>
        <p class="nota">Estos son los datos de la cuenta de correo que va a usar el sistema para enviar el enlace de gestión de turnos a los clientes. Se los tiene que dar tu proveedor de hosting/email (por ejemplo, la cuenta de correo creada en el panel de Ferozo).</p>

        <form action="{{ route('administracion.configuracion.correo.update') }}" method="POST">
            @csrf

            <label for="mail_host">Servidor (host)</label>
            <input type="text" name="mail_host" id="mail_host" required placeholder="Ej. mail.tudominio.com" value="{{ old('mail_host', $configuracion->mail_host) }}">

            <div class="row">
                <div>
                    <label for="mail_encryption">Cifrado</label>
                    <select name="mail_encryption" id="mail_encryption">
                        <option value="" {{ old('mail_encryption', $configuracion->mail_encryption) === null ? 'selected' : '' }}>Ninguno</option>
                        <option value="tls" {{ old('mail_encryption', $configuracion->mail_encryption) === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ old('mail_encryption', $configuracion->mail_encryption) === 'ssl' ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>
                <div>
                    <label for="mail_port">Puerto</label>
                    <input type="number" name="mail_port" id="mail_port" required placeholder="587" value="{{ old('mail_port', $configuracion->mail_port) }}">
                </div>
            </div>

            <label for="mail_username">Usuario</label>
            <input type="text" name="mail_username" id="mail_username" required placeholder="turnos@tudominio.com" value="{{ old('mail_username', $configuracion->mail_username) }}">

            <label for="mail_password">Contraseña</label>
            <input type="password" name="mail_password" id="mail_password" placeholder="{{ $configuracion->estaConfigurado() ? 'Dejar en blanco para no cambiarla' : '' }}">

            <label for="mail_from_address">Email remitente</label>
            <input type="email" name="mail_from_address" id="mail_from_address" required placeholder="turnos@tudominio.com" value="{{ old('mail_from_address', $configuracion->mail_from_address) }}">

            <label for="mail_from_name">Nombre remitente</label>
            <input type="text" name="mail_from_name" id="mail_from_name" required placeholder="Frío Center" value="{{ old('mail_from_name', $configuracion->mail_from_name ?? 'Frío Center') }}">

            <button type="submit" class="guardar">Guardar configuración</button>
        </form>
    </div>

    @if ($configuracion->estaConfigurado())
        <div class="card">
            <h2>Probar envío</h2>
            <p class="nota">Mandate un correo de prueba a tu propia casilla para confirmar que la configuración funciona.</p>
            <form action="{{ route('administracion.configuracion.correo.probar') }}" method="POST" class="fila-prueba">
                @csrf
                <input type="email" name="destinatario" placeholder="tu-email@ejemplo.com" required>
                <button type="submit" class="probar">Enviar prueba</button>
            </form>
        </div>
    @endif
</main>
</body>
</html>
