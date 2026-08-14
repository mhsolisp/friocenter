<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frío Center — Rosario</title>
    <style>
        :root { --navy: #0f2a47; --navy-deep: #081a2e; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); color: #16222e; }

        header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.25rem 1.75rem; background: #fff; border-bottom: 1px solid var(--line);
        }
        header img { height: 40px; }
        header a.login-link {
            color: var(--navy); text-decoration: none; font-weight: 700; font-size: 0.9rem;
            border: 1px solid var(--navy); padding: 0.5rem 1.1rem; border-radius: 8px;
        }
        header a.login-link:hover { background: var(--navy); color: #fff; }

        .hero {
            background: linear-gradient(135deg, var(--navy-deep), var(--navy) 70%);
            color: #fff; padding: 3.5rem 1.75rem 4.5rem; text-align: center; position: relative; overflow: hidden;
        }
        .hero::after {
            content: "❄"; position: absolute; right: -1rem; top: -2rem; font-size: 11rem;
            color: rgba(255,255,255,0.06); transform: rotate(15deg);
        }
        .hero .kicker { color: var(--ice); font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; font-size: 0.82rem; }
        .hero h1 { font-size: 2.1rem; margin: 0.5rem 0 0.75rem; }
        .hero p { max-width: 32rem; margin: 0 auto; color: rgba(255,255,255,0.8); }
        .hero .cta {
            display: inline-block; margin-top: 1.75rem; background: var(--ice); color: #072033;
            text-decoration: none; font-weight: 800; padding: 0.9rem 2rem; border-radius: 10px; font-size: 1.05rem;
        }
        .hero .cta:hover { background: #4fb9d8; }

        main { max-width: 40rem; margin: -2rem auto 3rem; padding: 0 1.5rem; }

        .card {
            background: #fff; border: 1px solid var(--line); border-radius: 12px;
            padding: 2rem; box-shadow: 0 12px 30px -18px rgba(8,26,46,0.35);
        }
        .card h2 { color: var(--navy); margin: 0 0 1.25rem; font-size: 1.25rem; text-align: center; }

        .item { display: flex; gap: 0.85rem; margin-bottom: 1.1rem; }
        .item .icono { color: var(--ice); font-size: 1.3rem; line-height: 1.4; flex-shrink: 0; }
        .item p { margin: 0; line-height: 1.5; }

        .destacado {
            background: var(--ice-light); border-radius: 8px; padding: 1rem 1.1rem;
            margin-top: 1.25rem; font-size: 0.92rem; line-height: 1.5;
        }

        footer {
            max-width: 40rem; margin: 0 auto 3rem; padding: 0 1.5rem;
            display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;
            color: #5b6b78; font-size: 0.88rem;
        }
        footer a { color: #5b6b78; text-decoration: none; }
        footer a:hover { color: var(--navy); }
    </style>
</head>
<body>
<header>
    <img src="{{ asset('images/logo-frio.png') }}" alt="Frío Center">
    <a href="{{ route('login') }}" class="login-link">Ingresar</a>
</header>

<div class="hero">
    <div class="kicker">Frío Center · Rosario</div>
    <h1>Aire acondicionado de tu vehículo, en buenas manos</h1>
    <p>Reservá el horario en el que nos traés tu auto para diagnóstico y presupuesto.</p>
    <a href="{{ route('turnos.nuevo') }}" class="cta">Sacar turno</a>
</div>

<main>
    <div class="card">
        <h2>Nuestra atención</h2>

        <div class="item">
            <span class="icono">❄</span>
            <p>De lunes a viernes de 8 a 17 hs.</p>
        </div>

        <div class="item">
            <span class="icono">❄</span>
            <p>Atendemos con turno previo: reservá tu horario online y traé tu vehículo con un teléfono de contacto, para realizar un diagnóstico y presupuesto de la reparación.</p>
        </div>

        <div class="item">
            <span class="icono">❄</span>
            <p>Tiempo estimativo de la revisión: de la mañana a primera hora de la tarde.</p>
        </div>

        <div class="destacado">
            De retirar el vehículo sin realizar el trabajo deberá abonar <strong>$40.000</strong> (valor del diagnóstico y presupuesto).
        </div>
    </div>
</main>

<footer>
    <a href="https://instagram.com/frio.center" target="_blank" rel="noopener">📷 @frio.center</a>
    <span>📍 Cochabamba 1641, Rosario</span>
</footer>
</body>
</html>
