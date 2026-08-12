<?php

namespace App\Http\Controllers;

use App\Models\BloqueoAgenda;
use App\Models\Cliente;
use App\Models\ConfiguracionAgenda;
use App\Models\Marca;
use App\Models\Turno;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TurnoController extends Controller
{
    /**
     * GET /turnos/nuevo
     * Muestra el formulario público de "Sacar turno".
     */
    public function nuevo()
    {
        $marcas = Marca::with('modelos')->orderBy('nombre')->get();

        return view('turnos.nuevo', compact('marcas'));
    }

    /**
     * GET /api/turnos/buscar-patente?patente=AA000AA
     * Autocompletado: si la patente ya existe, devuelve los datos del
     * vehículo y del cliente para que el frontend pregunte "¿seguís siendo
     * el titular?" antes de completar el formulario.
     */
    public function buscarPatente(Request $request)
    {
        $patente = strtoupper(trim($request->query('patente', '')));

        $vehiculo = Vehiculo::with(['cliente', 'modelo.marca'])
            ->where('patente', $patente)
            ->first();

        if (!$vehiculo) {
            return response()->json(['encontrado' => false]);
        }

        return response()->json([
            'encontrado' => true,
            'vehiculo_id' => $vehiculo->id,
            'cliente_id' => $vehiculo->cliente_id,
            'nombre_titular' => $vehiculo->cliente->nombre_apellido,
            'cliente' => [
                'nombre_apellido' => $vehiculo->cliente->nombre_apellido,
                'condicion_fiscal' => $vehiculo->cliente->condicion_fiscal,
                'dni' => $vehiculo->cliente->dni,
                'cuit' => $vehiculo->cliente->cuit,
                'razon_social' => $vehiculo->cliente->razon_social,
                'condicion_iva' => $vehiculo->cliente->condicion_iva,
                'telefono' => $vehiculo->cliente->telefono,
                'email' => $vehiculo->cliente->email,
            ],
            'vehiculo' => [
                'marca_id' => $vehiculo->modelo?->marca_id,
                'modelo_id' => $vehiculo->modelo_id,
                'vehiculo_otro' => $vehiculo->vehiculo_otro,
                'anio' => $vehiculo->anio,
                'color' => $vehiculo->color,
            ],
        ]);
    }

    /**
     * GET /api/turnos/disponibilidad?fecha=2026-08-20
     * Calcula los horarios disponibles para una fecha, según la
     * configuración de agenda de Administración y los bloqueos vigentes.
     */
    public function disponibilidad(Request $request)
    {
        $request->validate(['fecha' => 'required|date|after_or_equal:today']);

        $fecha = Carbon::parse($request->query('fecha'));
        $slots = $this->calcularSlotsDisponibles($fecha);

        return response()->json(['fecha' => $fecha->toDateString(), 'horarios' => $slots]);
    }

    /**
     * Devuelve los horarios de un día con la cantidad de cupos libres en cada uno.
     * Un slot con cupos_disponibles = 0 no se debe ofrecer en el frontend.
     */
    protected function calcularSlotsDisponibles(Carbon $fecha): array
    {
        // 1. ¿Hay un bloqueo (feriado, vacaciones, imprevisto) que cubra esta fecha?
        $bloqueado = BloqueoAgenda::where('fecha_inicio', '<=', $fecha->toDateString())
            ->where('fecha_fin', '>=', $fecha->toDateString())
            ->exists();

        if ($bloqueado) {
            return [];
        }

        // 2. Configuración de agenda para ese día de la semana (0=domingo..6=sábado).
        $config = ConfiguracionAgenda::where('dia_semana', $fecha->dayOfWeek)
            ->where('activo', true)
            ->first();

        if (!$config) {
            return []; // Día no habilitado para turnos (ej. domingo).
        }

        // 3. Generar los horarios posibles entre hora_inicio y hora_fin.
        $inicio = Carbon::parse($fecha->toDateString() . ' ' . $config->hora_inicio);
        $fin = Carbon::parse($fecha->toDateString() . ' ' . $config->hora_fin);

        $slots = [];
        for ($hora = $inicio->copy(); $hora->lessThan($fin); $hora->addMinutes($config->intervalo_minutos)) {
            $slots[] = $hora->format('H:i');
        }

        // 4. Contar turnos ya reservados (no cancelados) por horario.
        $ocupados = Turno::where('fecha_turno', $fecha->toDateString())
            ->where('estado', '!=', 'cancelado')
            ->selectRaw('hora_turno, COUNT(*) as cantidad')
            ->groupBy('hora_turno')
            ->pluck('cantidad', 'hora_turno');

        $ahora = now();

        return collect($slots)->map(function (string $horario) use ($config, $ocupados, $fecha, $ahora) {
            $ocupadosEnSlot = $ocupados[$horario . ':00'] ?? ($ocupados[$horario] ?? 0);
            $cupos = max(0, $config->turnos_por_franja - $ocupadosEnSlot);

            // No ofrecer horarios que ya pasaron si la fecha elegida es hoy.
            $horarioCompleto = Carbon::parse($fecha->toDateString() . ' ' . $horario);
            if ($fecha->isToday() && $horarioCompleto->lessThan($ahora)) {
                $cupos = 0;
            }

            return ['horario' => $horario, 'cupos_disponibles' => $cupos];
        })->values()->all();
    }

    /**
     * POST /turnos
     * Guarda un nuevo turno: crea o actualiza cliente y vehículo, valida
     * que el horario elegido siga disponible, y genera el token de gestión.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre_apellido' => 'required|string|max:150',
            'condicion_fiscal' => 'required|in:consumidor_final,factura',
            'dni' => 'required_if:condicion_fiscal,consumidor_final|nullable|regex:/^\d{7,8}$/',
            'cuit' => 'required_if:condicion_fiscal,factura|nullable|regex:/^\d{2}-?\d{8}-?\d{1}$/',
            'razon_social' => 'required_if:condicion_fiscal,factura|nullable|string|max:150',
            'condicion_iva' => 'required_if:condicion_fiscal,factura|nullable|in:responsable_inscripto,monotributo,exento,consumidor_final',
            'telefono' => 'required|string|max:30',
            'email' => 'required|email|max:150',

            'patente' => 'required|string|max:10',
            'marca_id' => 'required_without:vehiculo_otro|nullable|exists:marcas,id',
            'modelo_id' => 'required_without:vehiculo_otro|nullable|exists:modelos,id',
            'vehiculo_otro' => 'required_without:modelo_id|nullable|string|max:150',
            'anio' => 'nullable|integer|min:1970|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:40',

            'problematica' => 'required|string|max:1000',
            'fecha_turno' => 'required|date|after_or_equal:today',
            'hora_turno' => 'required|date_format:H:i',

            // Si el frontend ya sabe que la patente existe y el titular confirmó que es el mismo.
            'vehiculo_id_confirmado' => 'nullable|exists:vehiculos,id',
        ]);

        $patente = strtoupper(trim($datos['patente']));

        $turno = DB::transaction(function () use ($datos, $patente) {
            // Re-chequeo de disponibilidad server-side (por si alguien más tomó el horario mientras completaba el formulario).
            $slots = collect($this->calcularSlotsDisponibles(Carbon::parse($datos['fecha_turno'])));
            $slot = $slots->firstWhere('horario', $datos['hora_turno']);

            if (!$slot || $slot['cupos_disponibles'] <= 0) {
                throw ValidationException::withMessages([
                    'hora_turno' => 'Ese horario ya no está disponible. Por favor elegí otro.',
                ]);
            }

            // Cliente: si el frontend confirmó titularidad de un vehículo existente, reutilizamos ese cliente.
            if (!empty($datos['vehiculo_id_confirmado'])) {
                $vehiculoExistente = Vehiculo::findOrFail($datos['vehiculo_id_confirmado']);
                $cliente = $vehiculoExistente->cliente;
                $cliente->update([
                    'nombre_apellido' => $datos['nombre_apellido'],
                    'condicion_fiscal' => $datos['condicion_fiscal'],
                    'dni' => $datos['dni'] ?? null,
                    'cuit' => $datos['cuit'] ?? null,
                    'razon_social' => $datos['razon_social'] ?? null,
                    'condicion_iva' => $datos['condicion_iva'] ?? null,
                    'telefono' => $datos['telefono'],
                    'email' => $datos['email'],
                ]);
            } else {
                $cliente = Cliente::create([
                    'nombre_apellido' => $datos['nombre_apellido'],
                    'condicion_fiscal' => $datos['condicion_fiscal'],
                    'dni' => $datos['dni'] ?? null,
                    'cuit' => $datos['cuit'] ?? null,
                    'razon_social' => $datos['razon_social'] ?? null,
                    'condicion_iva' => $datos['condicion_iva'] ?? null,
                    'telefono' => $datos['telefono'],
                    'email' => $datos['email'],
                ]);
            }

            // Vehículo: se crea o se actualiza (por si cambió de titular o de datos) según la patente.
            $vehiculo = Vehiculo::updateOrCreate(
                ['patente' => $patente],
                [
                    'cliente_id' => $cliente->id,
                    'modelo_id' => $datos['modelo_id'] ?? null,
                    'vehiculo_otro' => $datos['vehiculo_otro'] ?? null,
                    'anio' => $datos['anio'] ?? null,
                    'color' => $datos['color'] ?? null,
                ]
            );

            return Turno::create([
                'cliente_id' => $cliente->id,
                'vehiculo_id' => $vehiculo->id,
                'problematica' => $datos['problematica'],
                'fecha_turno' => $datos['fecha_turno'],
                'hora_turno' => $datos['hora_turno'],
                'estado' => 'reservado',
            ]);
        });

        // TODO (próxima etapa): disparar notificación de confirmación por WhatsApp
        // y el mail con el enlace de gestión (turnos.gestionar, con $turno->token).

        return redirect()
            ->route('turnos.confirmado', $turno->token)
            ->with('ok', 'Turno reservado con éxito.');
    }

    /**
     * GET /turnos/confirmado/{token}
     * Pantalla de confirmación inmediatamente después de sacar el turno.
     */
    public function confirmado(string $token)
    {
        $turno = Turno::with(['cliente', 'vehiculo.modelo.marca'])
            ->where('token', $token)
            ->firstOrFail();

        return view('turnos.confirmado', compact('turno'));
    }

    /**
     * GET /turnos/gestionar/{token}
     * Pantalla a la que llega el cliente desde el enlace del mail:
     * puede ver los datos del turno, cancelarlo o reprogramarlo.
     */
    public function gestionar(string $token)
    {
        $turno = Turno::with(['cliente', 'vehiculo.modelo.marca'])
            ->where('token', $token)
            ->firstOrFail();

        return view('turnos.gestionar', compact('turno'));
    }

    /**
     * POST /turnos/gestionar/{token}/reprogramar
     */
    public function reprogramar(Request $request, string $token)
    {
        $turno = Turno::where('token', $token)->firstOrFail();

        if (!$turno->puede_gestionarse) {
            return back()->withErrors(['turno' => 'Ya no es posible reprogramar este turno (venció el plazo o cambió de estado).']);
        }

        $datos = $request->validate([
            'fecha_turno' => 'required|date|after_or_equal:today',
            'hora_turno' => 'required|date_format:H:i',
        ]);

        $slots = collect($this->calcularSlotsDisponibles(Carbon::parse($datos['fecha_turno'])));
        $slot = $slots->firstWhere('horario', $datos['hora_turno']);

        if (!$slot || $slot['cupos_disponibles'] <= 0) {
            return back()->withErrors(['hora_turno' => 'Ese horario ya no está disponible. Por favor elegí otro.']);
        }

        $turno->update([
            'fecha_turno' => $datos['fecha_turno'],
            'hora_turno' => $datos['hora_turno'],
        ]);

        // El mismo token/enlace sigue siendo válido, ahora hasta la nueva fecha y hora.
        // TODO (próxima etapa): reenviar WhatsApp/mail confirmando la reprogramación.

        return redirect()
            ->route('turnos.gestionar', $token)
            ->with('ok', 'Turno reprogramado con éxito.');
    }

    /**
     * POST /turnos/gestionar/{token}/cancelar
     */
    public function cancelar(string $token)
    {
        $turno = Turno::where('token', $token)->firstOrFail();

        if (!$turno->puede_gestionarse) {
            return back()->withErrors(['turno' => 'Ya no es posible cancelar este turno (venció el plazo de las 14 hs del día anterior, o ya cambió de estado).']);
        }

        $turno->update(['estado' => 'cancelado']);

        return redirect()
            ->route('turnos.gestionar', $token)
            ->with('ok', 'Turno cancelado.');
    }
}
