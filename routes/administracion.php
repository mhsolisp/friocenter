<?php

// -----------------------------------------------------------------------
// Rutas de Presupuestos — exclusivas de Administración. Copiar este
// bloque dentro de tu routes/web.php (o hacer un require de este
// archivo desde ahí, igual que con los otros).
// -----------------------------------------------------------------------

use App\Http\Controllers\Administracion\AgendaController;
use App\Http\Controllers\Administracion\BloqueoAgendaController;
use App\Http\Controllers\Administracion\ConfiguracionCorreoController;
use App\Http\Controllers\Administracion\MarcaController;
use App\Http\Controllers\Administracion\ModeloController;
use App\Http\Controllers\Administracion\PresupuestoController;
use App\Http\Controllers\Administracion\PresupuestoPdfController;
use App\Http\Controllers\Administracion\RecepcionController;
use App\Http\Controllers\Administracion\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'rol:administracion'])
    ->prefix('administracion')
    ->name('administracion.')
    ->group(function () {
        Route::get('/recibir', [RecepcionController::class, 'index'])->name('recibir.index');
        Route::post('/recibir/{turno}/confirmar', [RecepcionController::class, 'confirmar'])->name('recibir.confirmar');

        Route::get('/presupuestos', [PresupuestoController::class, 'index'])->name('presupuestos.index');
        Route::get('/presupuestos/historial', [PresupuestoController::class, 'historial'])->name('presupuestos.historial');
        Route::get('/turnos/{turno}/presupuesto', [PresupuestoController::class, 'edit'])->name('presupuestos.edit');
        Route::post('/turnos/{turno}/presupuesto', [PresupuestoController::class, 'store'])->name('presupuestos.store');
        Route::post('/turnos/{turno}/presupuesto/respuesta', [PresupuestoController::class, 'registrarRespuesta'])->name('presupuestos.respuesta');
        Route::get('/turnos/{turno}/presupuesto/pdf', [PresupuestoPdfController::class, 'descargar'])->name('presupuestos.pdf');
        Route::post('/turnos/{turno}/presupuesto/pdf/enviar', [PresupuestoPdfController::class, 'enviar'])->name('presupuestos.pdf.enviar');

        // Configuración de agenda: horarios semanales + bloqueo de fechas puntuales.
        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
        Route::post('/agenda', [AgendaController::class, 'update'])->name('agenda.update');
        Route::get('/agenda/bloqueos', [BloqueoAgendaController::class, 'index'])->name('agenda.bloqueos');
        Route::post('/agenda/bloqueos', [BloqueoAgendaController::class, 'store'])->name('agenda.bloqueos.store');
        Route::delete('/agenda/bloqueos/{bloqueo}', [BloqueoAgendaController::class, 'destroy'])->name('agenda.bloqueos.destroy');

        // Marcas y modelos del selector de vehículos.
        Route::get('/marcas', [MarcaController::class, 'index'])->name('marcas.index');
        Route::post('/marcas', [MarcaController::class, 'store'])->name('marcas.store');
        Route::delete('/marcas/{marca}', [MarcaController::class, 'destroy'])->name('marcas.destroy');
        Route::post('/marcas/{marca}/modelos', [ModeloController::class, 'store'])->name('modelos.store');
        Route::delete('/modelos/{modelo}', [ModeloController::class, 'destroy'])->name('modelos.destroy');

        // Gestión de usuarios (Administración y Taller).
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/nuevo', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::post('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::post('/usuarios/{usuario}/toggle', [UsuarioController::class, 'toggle'])->name('usuarios.toggle');

        // Configuración de correo (SMTP) usada para el enlace de gestión de turnos.
        Route::get('/configuracion/correo', [ConfiguracionCorreoController::class, 'edit'])->name('configuracion.correo');
        Route::post('/configuracion/correo', [ConfiguracionCorreoController::class, 'update'])->name('configuracion.correo.update');
        Route::post('/configuracion/correo/probar', [ConfiguracionCorreoController::class, 'probar'])->name('configuracion.correo.probar');
    });
