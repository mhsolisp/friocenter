<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * GET /administracion/usuarios
     */
    public function index()
    {
        $usuarios = User::orderBy('rol')->orderBy('name')->get();

        return view('administracion.usuarios.index', compact('usuarios'));
    }

    /**
     * GET /administracion/usuarios/nuevo
     */
    public function create()
    {
        return view('administracion.usuarios.crear');
    }

    /**
     * POST /administracion/usuarios
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:administracion,taller',
            'permiso_ver_dia' => 'nullable|boolean',
            'permiso_ver_dias_programados' => 'nullable|boolean',
            'permiso_ver_historial' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol' => $datos['rol'],
            'permiso_ver_dia' => (bool) ($datos['permiso_ver_dia'] ?? false),
            'permiso_ver_dias_programados' => (bool) ($datos['permiso_ver_dias_programados'] ?? false),
            'permiso_ver_historial' => (bool) ($datos['permiso_ver_historial'] ?? false),
            'activo' => true,
        ]);

        return redirect()
            ->route('administracion.usuarios.index')
            ->with('ok', 'Usuario creado correctamente.');
    }

    /**
     * GET /administracion/usuarios/{usuario}/editar
     */
    public function edit(User $usuario)
    {
        return view('administracion.usuarios.editar', compact('usuario'));
    }

    /**
     * POST /administracion/usuarios/{usuario}
     */
    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($usuario->id)],
            'rol' => 'required|in:administracion,taller',
            'permiso_ver_dia' => 'nullable|boolean',
            'permiso_ver_dias_programados' => 'nullable|boolean',
            'permiso_ver_historial' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];
        $usuario->rol = $datos['rol'];
        $usuario->permiso_ver_dia = (bool) ($datos['permiso_ver_dia'] ?? false);
        $usuario->permiso_ver_dias_programados = (bool) ($datos['permiso_ver_dias_programados'] ?? false);
        $usuario->permiso_ver_historial = (bool) ($datos['permiso_ver_historial'] ?? false);

        if (! empty($datos['password'])) {
            $usuario->password = Hash::make($datos['password']);
        }

        $usuario->save();

        return redirect()
            ->route('administracion.usuarios.index')
            ->with('ok', 'Usuario actualizado correctamente.');
    }

    /**
     * POST /administracion/usuarios/{usuario}/toggle
     * Activa/desactiva el acceso del usuario, sin borrarlo (mantiene su
     * historial de órdenes de trabajo y presupuestos cargados).
     */
    public function toggle(Request $request, User $usuario)
    {
        if ($usuario->id === $request->user()->id) {
            return back()->withErrors(['usuario' => 'No podés desactivar tu propio usuario.']);
        }

        $usuario->update(['activo' => ! $usuario->activo]);

        return redirect()
            ->route('administracion.usuarios.index')
            ->with('ok', $usuario->activo ? 'Usuario activado.' : 'Usuario desactivado.');
    }
}
