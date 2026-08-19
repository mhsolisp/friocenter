<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\CierreCaja;
use App\Models\MetodoPago;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    /**
     * GET /administracion/caja
     * Caja única del día (por defecto hoy, o la fecha pedida por
     * ?fecha=), con el detalle de movimientos y el reporte de importes
     * por método de pago que sirve tanto para mirar el día como para el
     * cierre.
     */
    public function index(Request $request)
    {
        $fecha = $request->date('fecha') ?? today();

        $metodos = MetodoPago::orderBy('nombre')->get();

        $movimientos = MovimientoCaja::where('fecha', $fecha->toDateString())
            ->with(['metodoPago', 'usuario'])
            ->orderBy('created_at')
            ->get();

        $totalesPorMetodo = $metodos
            ->map(fn ($metodo) => [
                'metodo' => $metodo,
                'ingresos' => $movimientos->where('metodo_pago_id', $metodo->id)->where('tipo', 'ingreso')->sum('monto'),
                'egresos' => $movimientos->where('metodo_pago_id', $metodo->id)->where('tipo', 'egreso')->sum('monto'),
            ])
            ->filter(fn ($t) => $t['ingresos'] > 0 || $t['egresos'] > 0)
            ->values();

        $totalIngresos = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('tipo', 'egreso')->sum('monto');

        $cierre = CierreCaja::with('usuario')->where('fecha', $fecha->toDateString())->first();

        return view('administracion.caja.index', compact(
            'fecha', 'metodos', 'movimientos', 'totalesPorMetodo',
            'totalIngresos', 'totalEgresos', 'cierre'
        ));
    }

    /**
     * POST /administracion/caja
     * La fecha viaja como campo oculto igual a la que se está mirando:
     * normalmente hoy, pero se puede cargar en un día ya cerrado (la UI
     * pide doble confirmación antes de mandar el form en ese caso).
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:150',
            'observaciones' => 'nullable|string',
        ]);

        MovimientoCaja::create($datos + ['usuario_id' => $request->user()->id]);

        return redirect()
            ->route('administracion.caja.index', ['fecha' => $datos['fecha']])
            ->with('ok', 'Movimiento registrado.');
    }

    /**
     * POST /administracion/caja/cerrar
     */
    public function cerrar(Request $request)
    {
        $datos = $request->validate(['fecha' => 'required|date']);

        if (CierreCaja::where('fecha', $datos['fecha'])->exists()) {
            return redirect()
                ->route('administracion.caja.index', ['fecha' => $datos['fecha']])
                ->withErrors('Ese día ya está cerrado.');
        }

        CierreCaja::create(['fecha' => $datos['fecha'], 'usuario_id' => $request->user()->id]);

        return redirect()
            ->route('administracion.caja.index', ['fecha' => $datos['fecha']])
            ->with('ok', 'Caja cerrada correctamente.');
    }
}
