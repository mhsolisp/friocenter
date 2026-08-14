<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * GET /administracion/marcas
     */
    public function index()
    {
        $marcas = Marca::withCount('modelos')->with('modelos')->orderBy('nombre')->get();

        return view('administracion.marcas.index', compact('marcas'));
    }

    /**
     * POST /administracion/marcas
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:60|unique:marcas,nombre',
        ]);

        Marca::create($datos);

        return redirect()->route('administracion.marcas.index')->with('ok', 'Marca agregada.');
    }

    /**
     * DELETE /administracion/marcas/{marca}
     * Al borrar la marca se borran también sus modelos (los vehículos que
     * ya tenían ese modelo cargado no se pierden: solo queda sin ese dato,
     * la patente y el historial siguen intactos).
     */
    public function destroy(Marca $marca)
    {
        $marca->delete();

        return redirect()->route('administracion.marcas.index')->with('ok', 'Marca eliminada.');
    }
}
