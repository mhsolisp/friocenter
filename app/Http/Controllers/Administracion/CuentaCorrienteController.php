<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class CuentaCorrienteController extends Controller
{
    /**
     * GET /administracion/cuentas-corrientes
     * Un pago en Caja no genera crédito acá solo: el saldo de cada
     * cliente sale únicamente de los movimientos cargados a mano en este
     * módulo.
     */
    public function index(Request $request)
    {
        $query = Cliente::query()
            ->withSum(['movimientosCuentaCorriente as total_cargo' => fn ($q) => $q->where('tipo', 'cargo')], 'monto')
            ->withSum(['movimientosCuentaCorriente as total_pago' => fn ($q) => $q->where('tipo', 'pago')], 'monto');

        if ($request->filled('buscar')) {
            $buscar = $request->string('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_apellido', 'like', "%{$buscar}%")
                    ->orWhere('dni', 'like', "%{$buscar}%")
                    ->orWhere('cuit', 'like', "%{$buscar}%");
            });
        }

        $clientes = $query->orderBy('nombre_apellido')->paginate(20)->withQueryString();

        return view('administracion.cuentas-corrientes.index', compact('clientes'));
    }

    /**
     * GET /administracion/cuentas-corrientes/{cliente}
     */
    public function show(Cliente $cliente)
    {
        $movimientos = $cliente->movimientosCuentaCorriente()
            ->with('usuario')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        $saldo = $movimientos->where('tipo', 'cargo')->sum('monto')
            - $movimientos->where('tipo', 'pago')->sum('monto');

        return view('administracion.cuentas-corrientes.show', compact('cliente', 'movimientos', 'saldo'));
    }

    /**
     * POST /administracion/cuentas-corrientes/{cliente}
     */
    public function store(Request $request, Cliente $cliente)
    {
        $datos = $request->validate([
            'tipo' => 'required|in:cargo,pago',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:150',
            'observaciones' => 'nullable|string',
        ]);

        $cliente->movimientosCuentaCorriente()->create($datos + [
            'fecha' => today(),
            'usuario_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('administracion.cuentas-corrientes.show', $cliente)
            ->with('ok', 'Movimiento registrado.');
    }
}
