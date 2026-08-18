<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\RubroProveedor;
use Illuminate\Http\Request;

class RubroProveedorController extends Controller
{
    /**
     * GET /administracion/proveedores/rubros
     */
    public function index()
    {
        $rubros = RubroProveedor::withCount('proveedores')->orderBy('nombre')->get();

        return view('administracion.proveedores.rubros', compact('rubros'));
    }

    /**
     * POST /administracion/proveedores/rubros
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:60|unique:rubros_proveedor,nombre',
        ]);

        RubroProveedor::create($datos);

        return redirect()->route('administracion.proveedores.rubros.index')->with('ok', 'Rubro agregado.');
    }

    /**
     * DELETE /administracion/proveedores/rubros/{rubro}
     * Los proveedores que ya tenían este rubro no se borran, solo quedan
     * sin ese dato (igual que al borrar una marca de vehículo).
     */
    public function destroy(RubroProveedor $rubro)
    {
        $rubro->delete();

        return redirect()->route('administracion.proveedores.rubros.index')->with('ok', 'Rubro eliminado.');
    }
}
