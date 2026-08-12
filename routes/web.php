<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/turnos.php';
require __DIR__.'/auth.php';
require __DIR__.'/taller.php';
require __DIR__.'/administracion.php';
