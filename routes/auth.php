<?php

// -----------------------------------------------------------------------
// Rutas de autenticación y paneles internos (Administración / Taller).
// Copiar este bloque dentro de tu routes/web.php (o hacer un require
// de este archivo desde ahí, igual que con routes/turnos.php).
// -----------------------------------------------------------------------

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Administracion\DashboardController as AdministracionDashboard;
use App\Http\Controllers\Taller\DashboardController as TallerDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'mostrarLogin'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Panel de Administración: solo rol administracion (por diseño del middleware).
Route::middleware(['auth', 'rol:administracion'])
    ->prefix('administracion')
    ->name('administracion.')
    ->group(function () {
        Route::get('/', [AdministracionDashboard::class, 'index'])->name('dashboard');
        // Acá se van a sumar: configuración de agenda, marcas/modelos, usuarios, presupuestos...
    });

// Panel de Taller: accesible por rol taller, y también por administracion
// (el middleware VerificarRol deja pasar siempre a Administración).
Route::middleware(['auth', 'rol:taller'])
    ->prefix('taller')
    ->name('taller.')
    ->group(function () {
        Route::get('/', [TallerDashboard::class, 'index'])->name('dashboard');
        // Acá se van a sumar: búsqueda por patente, historial, orden de trabajo...
    });
