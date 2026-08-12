<?php

// -----------------------------------------------------------------------
// Rutas del módulo de Turnos.
// Copiar este bloque dentro de tu routes/web.php (o hacer un require
// de este archivo desde ahí). No requieren login: son públicas.
// -----------------------------------------------------------------------

use App\Http\Controllers\TurnoController;
use Illuminate\Support\Facades\Route;

Route::get('/turnos/nuevo', [TurnoController::class, 'nuevo'])->name('turnos.nuevo');
Route::post('/turnos', [TurnoController::class, 'store'])->name('turnos.store');
Route::get('/turnos/confirmado/{token}', [TurnoController::class, 'confirmado'])->name('turnos.confirmado');

Route::get('/turnos/gestionar/{token}', [TurnoController::class, 'gestionar'])->name('turnos.gestionar');
Route::post('/turnos/gestionar/{token}/reprogramar', [TurnoController::class, 'reprogramar'])->name('turnos.reprogramar');
Route::post('/turnos/gestionar/{token}/cancelar', [TurnoController::class, 'cancelar'])->name('turnos.cancelar');

// Endpoints usados por JavaScript del formulario (AJAX).
Route::get('/api/turnos/buscar-patente', [TurnoController::class, 'buscarPatente'])->name('turnos.buscar-patente');
Route::get('/api/turnos/disponibilidad', [TurnoController::class, 'disponibilidad'])->name('turnos.disponibilidad');
