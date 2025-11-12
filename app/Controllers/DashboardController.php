<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        // Simplemente cargamos una vista que crearemos
        return view('dashboard/index');
    }
}