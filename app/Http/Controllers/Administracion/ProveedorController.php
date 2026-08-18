<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use App\Models\RubroProveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    /**
     * GET /administracion/proveedores
     */
    public function index(Request $request)
    {
        $query = Proveedor::query()->with('rubro');

        if ($request->filled('buscar')) {
            $buscar = $request->string('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('razon_social', 'like', "%{$buscar}%")
                    ->orWhere('cuit', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('rubro_id')) {
            $query->where('rubro_id', $request->integer('rubro_id'));
        }

        if ($request->input('estado') === 'inactivos') {
            $query->where('activo', false);
        } else {
            $query->where('activo', true);
        }

        $proveedores = $query->orderBy('razon_social')->paginate(20)->withQueryString();
        $rubros = RubroProveedor::orderBy('nombre')->get();

        return view('administracion.proveedores.index', compact('proveedores', 'rubros'));
    }

    /**
     * GET /administracion/proveedores/nuevo
     */
    public function create()
    {
        $rubros = RubroProveedor::orderBy('nombre')->get();

        return view('administracion.proveedores.crear', compact('rubros'));
    }

    /**
     * POST /administracion/proveedores
     */
    public function store(Request $request)
    {
        $datos = $this->validarDatos($request);

        Proveedor::create($datos + ['activo' => true]);

        return redirect()
            ->route('administracion.proveedores.index')
            ->with('ok', 'Proveedor creado correctamente.');
    }

    /**
     * GET /administracion/proveedores/{proveedor}/editar
     */
    public function edit(Proveedor $proveedor)
    {
        $rubros = RubroProveedor::orderBy('nombre')->get();

        return view('administracion.proveedores.editar', compact('proveedor', 'rubros'));
    }

    /**
     * POST /administracion/proveedores/{proveedor}
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $datos = $this->validarDatos($request, $proveedor);

        $proveedor->update($datos);

        return redirect()
            ->route('administracion.proveedores.index')
            ->with('ok', 'Proveedor actualizado correctamente.');
    }

    /**
     * POST /administracion/proveedores/{proveedor}/toggle
     * Baja lógica: no se borra, para no perder el historial de pagos ya
     * registrados en Caja / Cuenta corriente.
     */
    public function toggle(Proveedor $proveedor)
    {
        $proveedor->update(['activo' => ! $proveedor->activo]);

        return redirect()
            ->route('administracion.proveedores.index')
            ->with('ok', $proveedor->activo ? 'Proveedor activado.' : 'Proveedor desactivado.');
    }

    private function validarDatos(Request $request, ?Proveedor $proveedor = null): array
    {
        return $request->validate([
            'razon_social' => 'required|string|max:150',
            'cuit' => ['required', 'string', 'max:15', Rule::unique('proveedores', 'cuit')->ignore($proveedor?->id)],
            'condicion_fiscal' => 'nullable|in:consumidor_final,factura',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'rubro_id' => 'nullable|exists:rubros_proveedor,id',
            'direccion' => 'nullable|string|max:200',
            'observaciones' => 'nullable|string',
        ]);
    }
}
