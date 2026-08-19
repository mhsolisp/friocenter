<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    /**
     * GET /administracion/caja/metodos
     */
    public function index()
    {
        $metodos = MetodoPago::withCount('movimientos')->orderBy('nombre')->get();

        return view('administracion.caja.metodos', compact('metodos'));
    }

    /**
     * POST /administracion/caja/metodos
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:60|unique:metodos_pago,nombre',
        ]);

        MetodoPago::create($datos);

        return redirect()->route('administracion.caja.metodos.index')->with('ok', 'Método de pago agregado.');
    }

    /**
     * DELETE /administracion/caja/metodos/{metodo}
     * A diferencia de los rubros de proveedor, acá no se puede borrar un
     * método que ya tiene movimientos: rompería los totales de los
     * cierres de caja ya hechos.
     */
    public function destroy(MetodoPago $metodo)
    {
        if ($metodo->movimientos()->exists()) {
            return redirect()
                ->route('administracion.caja.metodos.index')
                ->withErrors('No se puede eliminar: ya tiene movimientos de caja registrados.');
        }

        $metodo->delete();

        return redirect()->route('administracion.caja.metodos.index')->with('ok', 'Método de pago eliminado.');
    }
}
