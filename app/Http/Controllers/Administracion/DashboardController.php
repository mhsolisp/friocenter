<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('administracion.dashboard');
    }
}
