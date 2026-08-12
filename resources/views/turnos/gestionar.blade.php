<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar turno — Frío Center</title>
    <style>
        :root { --navy: #0f2a47; --ice: #3ea8c9; --ice-light: #eaf6fa; --frost: #f5f8fa; --line: #dde6ea; --error: #c0392b; }
        * { box-sizing: border-box; }
        body { font-family: -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--frost); margin: 0; color: #16222e; }
        header { background: var(--navy); color: #fff; padding: 1.75rem 1.25rem; }
        header .brand { font-size: 0.8rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--ice); font-weight: 600; }
        header h1 { margin: 0.3rem 0 0; font-size: 1.5rem; }
        main { max-width: 34rem; margin: -1.5rem auto 3rem; padding: 0 1.25rem; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .estado-badge { display: inline-block; padding: 0.25rem 0.7rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; background: var(--ice-light); color: var(--navy); }
        dt { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em; color: #5b6b78; margin-top: 0.85rem; }
        dt:first-child { margin-top: 0; }
        dd { margin: 0.1rem 0 0; font-weight: 600; color: var(--navy); }
        .acciones { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1.25rem; }
        button, .btn { border: none; border-radius: 8px; padding: 0.7rem 1.2rem; font-weight: 700; cursor: pointer; font-size: 0.92rem; }
        .btn-primario { background: var(--navy); color: #fff; }
        .btn-peligro { background: #fff; color: var(--error); border: 1px solid var(--error); }
        .alerta { background: #fdecea; border: 1px solid var(--error); color: var(--error); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.88rem; }
        .ok { background: var(--ice-light); border: 1px solid var(--ice); color: var(--navy); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .no-editable { color: #5b6b78; font-size: 0.9rem; }
        input[type=date], select { width: 100%; padding: 0.6rem 0.7rem; border: 1px solid var(--line); border-radius: 8px; font-size: 0.9rem; margin-bottom: 0.75rem; }
        .slots { display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 0.5rem; margin-bottom: 1rem; }
        .slot-btn { padding: 0.5rem 0.3rem; border: 1px solid var(--line); border-radius: 8px; background: #fff; cursor: pointer; font-size: 0.82rem; font-weight: 600; color: var(--navy); }
        .slot-btn.selected { background: var(--navy); color: #fff; }
        .slot-btn:disabled { opacity: 0.35; text-decoration: line-through; cursor: not-allowed; }
    </style>
</head>
<body>
<header>
    <div class="brand">Frío Center · Rosario</div>
    <h1>Tu turno</h1>
</header>
<main>

    @if (session('ok'))
        <div class="ok">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="alerta">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <span class="estado-badge">{{ str_replace('_', ' ', $turno->estado) }}</span>

        <dt>Fecha y hora</dt>
        <dd>{{ $turno->fecha_turno->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($turno->hora_turno)->format('H:i') }} hs</dd>

        <dt>Vehículo</dt>
        <dd>{{ $turno->vehiculo->descripcion }} · {{ $turno->vehiculo->patente }}</dd>

        <dt>Problemática informada</dt>
        <dd>{{ $turno->problematica }}</dd>
    </div>

    @if ($turno->puede_gestionarse)
        <div class="card">
            <h2 style="color: var(--navy); font-size: 1.05rem; margin-top:0;">Reprogramar turno</h2>
            <form action="{{ route('turnos.reprogramar', $turno->token) }}" method="POST" id="form-reprogramar">
                @csrf
                <label for="fecha_turno">Nueva fecha</label>
                <input type="date" name="fecha_turno" id="fecha_turno" min="{{ date('Y-m-d') }}" required>
                <div id="contenedor-slots" class="no-editable">Elegí una fecha para ver horarios disponibles.</div>
                <input type="hidden" name="hora_turno" id="hora_turno" required>
                <div class="acciones">
                    <button type="submit" class="btn-primario">Confirmar reprogramación</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2 style="color: var(--navy); font-size: 1.05rem; margin-top:0;">Cancelar turno</h2>
            <p class="no-editable">Podés cancelar hasta las 14 hs del día anterior al turno.</p>
            <form action="{{ route('turnos.cancelar', $turno->token) }}" method="POST"
                  onsubmit="return confirm('¿Confirmás que querés cancelar el turno?');">
                @csrf
                <button type="submit" class="btn-peligro">Cancelar turno</button>
            </form>
        </div>
    @else
        <p class="no-editable">
            @if ($turno->estado === 'cancelado')
                Este turno ya fue cancelado.
            @elseif ($turno->estado === 'reservado')
                Ya venció el plazo para cancelar o reprogramar este turno (hasta las 14 hs del día anterior).
            @else
                Este turno ya está en curso y no se puede modificar desde acá. Ante cualquier duda, contactanos directamente.
            @endif
        </p>
    @endif

</main>

<script>
(function () {
    const fechaInput = document.getElementById('fecha_turno');
    if (!fechaInput) return;

    const contenedorSlots = document.getElementById('contenedor-slots');
    const horaTurnoInput = document.getElementById('hora_turno');

    fechaInput.addEventListener('change', async function () {
        horaTurnoInput.value = '';
        contenedorSlots.innerHTML = 'Buscando horarios disponibles...';

        try {
            const resp = await fetch(`{{ route('turnos.disponibilidad') }}?fecha=${this.value}`);
            const data = await resp.json();

            if (!data.horarios.length) {
                contenedorSlots.innerHTML = 'No hay turnos disponibles para ese día.';
                return;
            }

            contenedorSlots.innerHTML = '<div class="slots"></div>';
            const cont = contenedorSlots.querySelector('.slots');

            data.horarios.forEach(h => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'slot-btn';
                btn.textContent = h.horario;
                btn.disabled = h.cupos_disponibles <= 0;
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    horaTurnoInput.value = h.horario;
                });
                cont.appendChild(btn);
            });
        } catch (e) {
            contenedorSlots.innerHTML = 'No pudimos cargar los horarios.';
        }
    });

    document.getElementById('form-reprogramar').addEventListener('submit', function (e) {
        if (!horaTurnoInput.value) {
            e.preventDefault();
            alert('Elegí un horario disponible.');
        }
    });
})();
</script>
</body>
</html>
