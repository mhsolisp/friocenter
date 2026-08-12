<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacar turno — Frío Center</title>
    <style>
        :root {
            --navy: #0f2a47;
            --navy-deep: #081a2e;
            --ice: #3ea8c9;
            --ice-light: #eaf6fa;
            --frost: #f5f8fa;
            --paper: #ffffff;
            --ink: #16222e;
            --ink-soft: #5b6b78;
            --line: #dde6ea;
            --error: #c0392b;
            --radius: 10px;
            font-family: -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--frost);
            color: var(--ink);
            line-height: 1.5;
        }

        header.top {
            background: linear-gradient(135deg, var(--navy-deep), var(--navy) 70%);
            color: #fff;
            padding: 2.5rem 1.5rem 3.5rem;
            position: relative;
            overflow: hidden;
        }

        header.top::after {
            content: "❄";
            position: absolute;
            right: -1rem;
            top: -1rem;
            font-size: 9rem;
            color: rgba(255,255,255,0.06);
            transform: rotate(15deg);
        }

        header.top .brand {
            font-size: 0.85rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--ice);
            font-weight: 600;
        }

        header.top h1 {
            margin: 0.4rem 0 0.6rem;
            font-size: 1.9rem;
            font-weight: 700;
        }

        header.top p {
            max-width: 40rem;
            color: rgba(255,255,255,0.78);
            margin: 0;
        }

        main {
            max-width: 44rem;
            margin: -2rem auto 3rem;
            padding: 0 1.25rem;
        }

        .card {
            background: var(--paper);
            border-radius: var(--radius);
            box-shadow: 0 12px 30px -18px rgba(8,26,46,0.35);
            padding: 1.75rem;
            margin-bottom: 1.25rem;
            border: 1px solid var(--line);
        }

        .modalidad {
            border-left: 3px solid var(--ice);
        }

        .modalidad strong { color: var(--navy); }

        .step-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--ice);
            margin-bottom: 0.35rem;
        }

        .card h2 {
            margin: 0 0 1rem;
            font-size: 1.05rem;
            color: var(--navy);
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.3rem;
        }

        .field { margin-bottom: 1rem; }

        input[type=text], input[type=email], input[type=tel], input[type=number], select, textarea {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 0.95rem;
            background: #fff;
            color: var(--ink);
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid var(--ice);
            outline-offset: 1px;
            border-color: var(--ice);
        }

        textarea { resize: vertical; min-height: 4.5rem; }

        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem; }
        @media (max-width: 560px) { .row { grid-template-columns: 1fr; } }

        .radio-group { display: flex; gap: 1.25rem; margin-bottom: 0.75rem; }
        .radio-option { display: flex; align-items: center; gap: 0.4rem; font-weight: 500; font-size: 0.92rem; }
        .radio-option input { width: auto; }

        .hint {
            font-size: 0.8rem;
            color: var(--ink-soft);
            margin-top: 0.25rem;
        }

        .titular-banner {
            display: none;
            background: var(--ice-light);
            border: 1px solid var(--ice);
            border-radius: 8px;
            padding: 0.85rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .titular-banner button {
            margin-right: 0.5rem;
            margin-top: 0.5rem;
        }

        .slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(74px, 1fr));
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .slot-btn {
            padding: 0.55rem 0.4rem;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--navy);
            text-align: center;
        }

        .slot-btn:hover { border-color: var(--ice); }
        .slot-btn.selected { background: var(--navy); color: #fff; border-color: var(--navy); }
        .slot-btn:disabled { opacity: 0.35; cursor: not-allowed; text-decoration: line-through; }

        button.btn-secundario, .slot-btn.confirm {
            background: #fff;
            border: 1px solid var(--navy);
            color: var(--navy);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        button.btn-primario {
            background: var(--navy);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .submit-row {
            display: flex;
            justify-content: flex-end;
        }

        .submit-row button {
            background: var(--navy);
            color: #fff;
            border: none;
            padding: 0.85rem 1.8rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
        }

        .submit-row button:hover { background: var(--navy-deep); }

        .error-list {
            background: #fdecea;
            border: 1px solid var(--error);
            color: var(--error);
            border-radius: 8px;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.88rem;
        }
    </style>
</head>
<body>

<header class="top">
    <div class="brand">Frío Center · Rosario</div>
    <h1>Sacar turno</h1>
    <p>Reservá el horario en el que vas a traernos tu vehículo para diagnóstico y presupuesto. La reparación se coordina después, una vez aceptado el presupuesto.</p>
</header>

<main>

    @if ($errors->any())
        <div class="error-list">
            <strong>Revisá estos datos:</strong>
            <ul style="margin: 0.4rem 0 0 1.1rem; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card modalidad">
        <strong>Nuestra modalidad de trabajo:</strong> primero revisamos el vehículo y te enviamos un presupuesto con costo. El turno de hoy reserva solo ese momento de diagnóstico, no el tiempo completo de reparación.
    </div>

    <form action="{{ route('turnos.store') }}" method="POST" id="form-turno">
        @csrf
        <input type="hidden" name="vehiculo_id_confirmado" id="vehiculo_id_confirmado" value="">

        <div class="card">
            <div class="step-label">Paso 1</div>
            <h2>Tus datos</h2>

            <div class="titular-banner" id="titular-banner">
                Encontramos este vehículo registrado a nombre de <strong id="nombre-titular-encontrado"></strong>.
                ¿Seguís siendo el titular?
                <div>
                    <button type="button" class="btn-secundario" id="btn-si-titular">Sí, soy yo</button>
                    <button type="button" class="btn-secundario" id="btn-no-titular">No, cambió de dueño</button>
                </div>
            </div>

            <div class="field">
                <label for="nombre_apellido">Nombre y Apellido (o Razón Social)</label>
                <input type="text" name="nombre_apellido" id="nombre_apellido" required value="{{ old('nombre_apellido') }}">
            </div>

            <div class="field">
                <label>Condición fiscal</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="condicion_fiscal" value="consumidor_final" checked> Consumidor Final
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="condicion_fiscal" value="factura"> Factura
                    </label>
                </div>
            </div>

            <div class="field" id="campo-dni">
                <label for="dni">DNI</label>
                <input type="text" name="dni" id="dni" placeholder="Sin puntos, ej. 30123456">
            </div>

            <div id="campos-factura" style="display:none;">
                <div class="row">
                    <div class="field">
                        <label for="cuit">CUIT</label>
                        <input type="text" name="cuit" id="cuit" placeholder="20-12345678-9">
                    </div>
                    <div class="field">
                        <label for="razon_social">Razón Social</label>
                        <input type="text" name="razon_social" id="razon_social">
                    </div>
                </div>
                <div class="field">
                    <label for="condicion_iva">Condición frente al IVA</label>
                    <select name="condicion_iva" id="condicion_iva">
                        <option value="responsable_inscripto">Responsable Inscripto</option>
                        <option value="monotributo">Monotributo</option>
                        <option value="exento">Exento</option>
                        <option value="consumidor_final">Consumidor Final</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="field">
                    <label for="telefono">Teléfono (WhatsApp)</label>
                    <input type="tel" name="telefono" id="telefono" required value="{{ old('telefono') }}">
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}">
                    <div class="hint">Te enviamos acá el enlace para gestionar el turno.</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="step-label">Paso 2</div>
            <h2>Tu vehículo</h2>

            <div class="field">
                <label for="patente">Patente</label>
                <input type="text" name="patente" id="patente" required maxlength="10" placeholder="AA000AA o AAA000" style="text-transform: uppercase;">
            </div>

            <div class="row">
                <div class="field">
                    <label for="marca_id">Marca</label>
                    <select name="marca_id" id="marca_id">
                        <option value="">Seleccioná una marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                        @endforeach
                        <option value="otro">Otro (no está en la lista)</option>
                    </select>
                </div>
                <div class="field">
                    <label for="modelo_id">Modelo</label>
                    <select name="modelo_id" id="modelo_id" disabled>
                        <option value="">Elegí primero la marca</option>
                    </select>
                </div>
            </div>

            <div class="field" id="campo-vehiculo-otro" style="display:none;">
                <label for="vehiculo_otro">Marca y modelo (escribilo vos)</label>
                <input type="text" name="vehiculo_otro" id="vehiculo_otro" placeholder="Ej. Citroën C4 Lounge">
            </div>

            <div class="row">
                <div class="field">
                    <label for="anio">Año</label>
                    <input type="number" name="anio" id="anio" min="1970" max="{{ date('Y') + 1 }}">
                </div>
                <div class="field">
                    <label for="color">Color</label>
                    <input type="text" name="color" id="color">
                </div>
            </div>

            <div class="field">
                <label for="problematica">¿Qué problema tiene el aire acondicionado?</label>
                <textarea name="problematica" id="problematica" required placeholder="Ej. no enfría, hace ruido, pierde gas...">{{ old('problematica') }}</textarea>
            </div>
        </div>

        <div class="card">
            <div class="step-label">Paso 3</div>
            <h2>Fecha y horario</h2>

            <div class="field">
                <label for="fecha_turno">Fecha</label>
                <input type="date" name="fecha_turno" id="fecha_turno" required min="{{ date('Y-m-d') }}">
            </div>

            <div id="contenedor-slots">
                <div class="hint">Elegí primero una fecha para ver los horarios disponibles.</div>
            </div>
            <input type="hidden" name="hora_turno" id="hora_turno" required>
        </div>

        <div class="submit-row">
            <button type="submit">Confirmar turno</button>
        </div>
    </form>
</main>

<script>
(function () {
    const marcas = @json($marcas->keyBy('id'));

    const marcaSelect = document.getElementById('marca_id');
    const modeloSelect = document.getElementById('modelo_id');
    const campoOtro = document.getElementById('campo-vehiculo-otro');

    marcaSelect.addEventListener('change', function () {
        modeloSelect.innerHTML = '';
        if (this.value === 'otro') {
            modeloSelect.disabled = true;
            campoOtro.style.display = 'block';
            modeloSelect.name = '';
            return;
        }
        campoOtro.style.display = 'none';
        modeloSelect.name = 'modelo_id';

        const marca = marcas[this.value];
        if (!marca) {
            modeloSelect.disabled = true;
            modeloSelect.innerHTML = '<option value="">Elegí primero la marca</option>';
            return;
        }
        modeloSelect.disabled = false;
        modeloSelect.innerHTML = '<option value="">Seleccioná un modelo</option>' +
            marca.modelos.map(m => `<option value="${m.id}">${m.nombre}</option>`).join('');
    });

    // --- Autocompletado por patente ---
    const patenteInput = document.getElementById('patente');
    const banner = document.getElementById('titular-banner');
    const nombreTitularSpan = document.getElementById('nombre-titular-encontrado');
    const vehiculoIdConfirmado = document.getElementById('vehiculo_id_confirmado');
    let datosEncontrados = null;

    patenteInput.addEventListener('blur', async function () {
        const patente = this.value.trim().toUpperCase();
        if (patente.length < 6) return;

        try {
            const resp = await fetch(`{{ route('turnos.buscar-patente') }}?patente=${encodeURIComponent(patente)}`);
            const data = await resp.json();

            if (data.encontrado) {
                datosEncontrados = data;
                nombreTitularSpan.textContent = data.nombre_titular;
                banner.style.display = 'block';
            } else {
                banner.style.display = 'none';
                vehiculoIdConfirmado.value = '';
            }
        } catch (e) {
            console.error('No se pudo consultar la patente', e);
        }
    });

    document.getElementById('btn-si-titular').addEventListener('click', function () {
        if (!datosEncontrados) return;
        vehiculoIdConfirmado.value = datosEncontrados.vehiculo_id;

        document.getElementById('nombre_apellido').value = datosEncontrados.cliente.nombre_apellido;
        document.getElementById('telefono').value = datosEncontrados.cliente.telefono;
        document.getElementById('email').value = datosEncontrados.cliente.email;
        document.querySelector(`input[name=condicion_fiscal][value=${datosEncontrados.cliente.condicion_fiscal}]`).checked = true;
        document.querySelector('input[name=condicion_fiscal]:checked').dispatchEvent(new Event('change'));

        if (datosEncontrados.cliente.dni) document.getElementById('dni').value = datosEncontrados.cliente.dni;
        if (datosEncontrados.cliente.cuit) document.getElementById('cuit').value = datosEncontrados.cliente.cuit;
        if (datosEncontrados.cliente.razon_social) document.getElementById('razon_social').value = datosEncontrados.cliente.razon_social;
        if (datosEncontrados.cliente.condicion_iva) document.getElementById('condicion_iva').value = datosEncontrados.cliente.condicion_iva;

        if (datosEncontrados.vehiculo.marca_id) {
            marcaSelect.value = datosEncontrados.vehiculo.marca_id;
            marcaSelect.dispatchEvent(new Event('change'));
            setTimeout(() => { modeloSelect.value = datosEncontrados.vehiculo.modelo_id; }, 50);
        } else if (datosEncontrados.vehiculo.vehiculo_otro) {
            marcaSelect.value = 'otro';
            marcaSelect.dispatchEvent(new Event('change'));
            document.getElementById('vehiculo_otro').value = datosEncontrados.vehiculo.vehiculo_otro;
        }
        document.getElementById('anio').value = datosEncontrados.vehiculo.anio || '';
        document.getElementById('color').value = datosEncontrados.vehiculo.color || '';

        banner.style.display = 'none';
    });

    document.getElementById('btn-no-titular').addEventListener('click', function () {
        vehiculoIdConfirmado.value = '';
        banner.style.display = 'none';
    });

    // Toggle condición fiscal
    document.querySelectorAll('input[name=condicion_fiscal]').forEach(radio => {
        radio.addEventListener('change', function () {
            const esFactura = this.value === 'factura';
            document.getElementById('campos-factura').style.display = esFactura ? 'block' : 'none';
            document.getElementById('campo-dni').style.display = esFactura ? 'none' : 'block';
        });
    });

    // --- Disponibilidad de horarios ---
    const fechaInput = document.getElementById('fecha_turno');
    const contenedorSlots = document.getElementById('contenedor-slots');
    const horaTurnoInput = document.getElementById('hora_turno');

    fechaInput.addEventListener('change', async function () {
        horaTurnoInput.value = '';
        contenedorSlots.innerHTML = '<div class="hint">Buscando horarios disponibles...</div>';

        try {
            const resp = await fetch(`{{ route('turnos.disponibilidad') }}?fecha=${this.value}`);
            const data = await resp.json();

            if (!data.horarios.length) {
                contenedorSlots.innerHTML = '<div class="hint">No hay turnos disponibles para ese día. Probá con otra fecha.</div>';
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
            contenedorSlots.innerHTML = '<div class="hint">No pudimos cargar los horarios. Intentá de nuevo.</div>';
        }
    });

    document.getElementById('form-turno').addEventListener('submit', function (e) {
        if (!horaTurnoInput.value) {
            e.preventDefault();
            alert('Elegí un horario disponible antes de confirmar.');
        }
    });
})();
</script>

</body>
</html>
