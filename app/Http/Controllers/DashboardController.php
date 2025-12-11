<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Cliente;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'osAbertas'        => OrdemServico::where('status', 'aberta')->count(),
            'osEmExecucao'     => OrdemServico::where('status', 'em_execucao')->count(),
            'osConcluidasHoje' => OrdemServico::where('status', 'concluida')
                                    ->whereDate('updated_at', now()->toDateString())
                                    ->count(),
            'clientesAtivos'   => Cliente::count(), // ou algum filtro seu
            'ordens'           => OrdemServico::with('cliente')
                                    ->latest()
                                    ->take(5)
                                    ->get(),
        ]);
    }
}
