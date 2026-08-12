<?php

// -----------------------------------------------------------------------
// Rutas internas de Taller: búsqueda por patente + historial + ingreso,
// y los listados de turnos del día / programados.
// Copiar este bloque dentro de tu routes/web.php (o hacer un require
// de este archivo desde ahí, igual que con los otros).
// -----------------------------------------------------------------------

use App\Http\Controllers\Taller\IngresoController;
use App\Http\Controllers\Taller\OrdenTrabajoController;
use App\Http\Controllers\Taller\TurnosHoyController;
use App\Http\Controllers\Taller\TurnosProgramadosController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rol:taller'])
    ->prefix('taller')
    ->name('taller.')
    ->group(function () {

        // Búsqueda por patente: accesible para cualquier usuario de Taller.
        // El historial dentro del resultado se filtra según el permiso
        // ver_historial de cada usuario (se resuelve en el controlador).
        Route::get('/ingreso', [IngresoController::class, 'index'])->name('ingreso.index');
        Route::post('/ingreso/{turno}/confirmar', [IngresoController::class, 'confirmar'])->name('ingreso.confirmar');

        // Orden de trabajo: tareas, repuestos y tiempo estimado. Nunca incluye
        // montos — eso es exclusivo de Administración (ver routes/administracion.php).
        Route::get('/turnos/{turno}/orden-trabajo', [OrdenTrabajoController::class, 'edit'])->name('orden-trabajo.edit');
        Route::post('/turnos/{turno}/orden-trabajo', [OrdenTrabajoController::class, 'update'])->name('orden-trabajo.update');

        // Estas dos sí están protegidas por permiso puntual.
        Route::get('/turnos-hoy', [TurnosHoyController::class, 'index'])
            ->middleware('permiso:ver_dia')
            ->name('turnos-hoy');

        Route::get('/turnos-programados', [TurnosProgramadosController::class, 'index'])
            ->middleware('permiso:ver_dias_programados')
            ->name('turnos-programados');
    });
