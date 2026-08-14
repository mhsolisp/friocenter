<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__.'/turnos.php';
require __DIR__.'/auth.php';
require __DIR__.'/taller.php';
require __DIR__.'/administracion.php';
