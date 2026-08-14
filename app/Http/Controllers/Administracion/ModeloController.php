<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    /**
     * POST /administracion/marcas/{marca}/modelos
     */
    public function store(Request $request, Marca $marca)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:60',
        ]);

        $existe = $marca->modelos()->where('nombre', $datos['nombre'])->exists();
        if ($existe) {
            return back()->withErrors(['nombre' => 'Ese modelo ya existe para '.$marca->nombre.'.']);
        }

        $marca->modelos()->create($datos);

        return redirect()->route('administracion.marcas.index')->with('ok', 'Modelo agregado a '.$marca->nombre.'.');
    }

    /**
     * DELETE /administracion/modelos/{modelo}
     */
    public function destroy(Modelo $modelo)
    {
        $modelo->delete();

        return redirect()->route('administracion.marcas.index')->with('ok', 'Modelo eliminado.');
    }
}
