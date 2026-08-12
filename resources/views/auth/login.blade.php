<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --navy-deep: #081a2e; --ice: #3ea8c9; --frost: #f5f8fa; --line: #dde6ea; --error: #c0392b; }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--navy-deep), var(--navy) 70%);
            font-family: -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .card {
            background: #fff; border-radius: 12px; padding: 2.25rem; width: 100%; max-width: 22rem;
            box-shadow: 0 20px 45px -20px rgba(0,0,0,0.5);
        }
        .brand { text-align: center; color: var(--ice); font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; font-size: 0.8rem; margin-bottom: 0.4rem; }
        h1 { text-align: center; color: var(--navy); margin: 0 0 1.5rem; font-size: 1.3rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; color: #16222e; }
        input[type=email], input[type=password] {
            width: 100%; padding: 0.65rem 0.75rem; border: 1px solid var(--line); border-radius: 8px;
            font-size: 0.95rem; margin-bottom: 1.1rem;
        }
        input:focus { outline: 2px solid var(--ice); outline-offset: 1px; }
        .checkbox-row { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 1.3rem; font-size: 0.85rem; }
        .checkbox-row input { width: auto; }
        button {
            width: 100%; background: var(--navy); color: #fff; border: none; padding: 0.75rem;
            border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer;
        }
        button:hover { background: var(--navy-deep); }
        .error { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.7rem 0.85rem; font-size: 0.85rem; margin-bottom: 1.1rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">Frío Center · Rosario</div>
        <h1>Ingresar al sistema</h1>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required autofocus value="{{ old('email') }}">

            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>

            <div class="checkbox-row">
                <input type="checkbox" name="recordarme" id="recordarme">
                <label for="recordarme" style="margin:0;">Recordarme</label>
            </div>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
