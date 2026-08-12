<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return $this->redirigirSegunRol();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credenciales, $request->boolean('recordarme'))) {
            return back()
                ->withErrors(['email' => 'Email o contraseña incorrectos.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if (!Auth::user()->activo) {
            Auth::logout();
            return back()->withErrors(['email' => 'Tu usuario está desactivado. Consultá con Administración.']);
        }

        return $this->redirigirSegunRol();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function redirigirSegunRol()
    {
        return Auth::user()->esAdministracion()
            ? redirect()->route('administracion.dashboard')
            : redirect()->route('taller.dashboard');
    }
}
