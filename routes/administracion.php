<?php

// -----------------------------------------------------------------------
// Rutas de Presupuestos — exclusivas de Administración. Copiar este
// bloque dentro de tu routes/web.php (o hacer un require de este
// archivo desde ahí, igual que con los otros).
// -----------------------------------------------------------------------

use App\Http\Controllers\Administracion\PresupuestoController;
use App\Http\Controllers\Administracion\RecepcionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rol:administracion'])
    ->prefix('administracion')
    ->name('administracion.')
    ->group(function () {
        Route::get('/recibir', [RecepcionController::class, 'index'])->name('recibir.index');
        Route::post('/recibir/{turno}/confirmar', [RecepcionController::class, 'confirmar'])->name('recibir.confirmar');

        Route::get('/presupuestos', [PresupuestoController::class, 'index'])->name('presupuestos.index');
        Route::get('/turnos/{turno}/presupuesto', [PresupuestoController::class, 'edit'])->name('presupuestos.edit');
        Route::post('/turnos/{turno}/presupuesto', [PresupuestoController::class, 'store'])->name('presupuestos.store');
        Route::post('/turnos/{turno}/presupuesto/respuesta', [PresupuestoController::class, 'registrarRespuesta'])->name('presupuestos.respuesta');
    });
