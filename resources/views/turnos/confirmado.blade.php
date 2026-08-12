<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turno confirmado — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --frost: #f5f8fa; --line: #dde6ea; }
        body { font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); margin: 0; color: #16222e; }
        main { max-width: 32rem; margin: 4rem auto; padding: 0 1.25rem; text-align: center; }
        .check { width: 64px; height: 64px; border-radius: 50%; background: var(--ice); color: #fff; font-size: 2rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; }
        h1 { color: var(--navy); margin-bottom: 0.5rem; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; text-align: left; margin-top: 1.5rem; }
        .card dt { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em; color: #5b6b78; margin-top: 0.75rem; }
        .card dt:first-child { margin-top: 0; }
        .card dd { margin: 0.1rem 0 0; font-weight: 600; color: var(--navy); }
        .hint { color: #5b6b78; font-size: 0.9rem; margin-top: 1.5rem; }
    </style>
</head>
<body>
<main>
    <div class="check">✓</div>
    <h1>¡Turno reservado!</h1>
    <p>Te enviamos la confirmación por WhatsApp y un enlace por email para gestionar tu turno cuando lo necesites.</p>

    <div class="card">
        <dt>Fecha y hora</dt>
        <dd>{{ $turno->fecha_turno->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($turno->hora_turno)->format('H:i') }} hs</dd>

        <dt>Vehículo</dt>
        <dd>{{ $turno->vehiculo->descripcion }} · {{ $turno->vehiculo->patente }}</dd>

        <dt>Problemática informada</dt>
        <dd>{{ $turno->problematica }}</dd>
    </div>

    <p class="hint">¿Necesitás cancelar o reprogramar? Usá el enlace que te llegó por email en cualquier momento hasta el día del turno.</p>
</main>
</body>
</html>
