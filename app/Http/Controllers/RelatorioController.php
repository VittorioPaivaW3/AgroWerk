<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function index()
    {
        // Depois podemos montar contagens aqui pra já ir alimentando alguns cards, se quiser
        return view('relatorios.index');
    }
}