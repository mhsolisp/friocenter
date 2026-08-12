<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    /**
     * Uso en rutas: ->middleware('rol:taller')  o  ->middleware('rol:administracion')
     *
     * Administración siempre pasa, sin importar qué rol se pida — según
     * definimos, Administración tiene acceso a todo lo que ve Taller.
     */
    public function handle(Request $request, Closure $next, string ...$rolesPermitidos): Response
    {
        $user = $request->user();

        if (!$user || !$user->activo) {
            abort(403, 'No tenés acceso a esta sección.');
        }

        if ($user->esAdministracion()) {
            return $next($request);
        }

        if (!in_array($user->rol, $rolesPermitidos, true)) {
            abort(403, 'No tenés acceso a esta sección.');
        }

        return $next($request);
    }
}
