<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CadasMaqController extends Controller
{
        public function index()
    {
        // Aqui depois você pode buscar dados do banco, etc.
        return view('cadastro/cadasmaq');
    }
}
