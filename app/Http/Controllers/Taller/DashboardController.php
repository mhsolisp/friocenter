<?php

namespace App\Http\Controllers\Taller;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('taller.dashboard');
    }
}
