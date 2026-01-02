<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardVisualizadorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // base sem order para contadores
        $base = OrdemServico::where('solicitante_id', $user->id);

        // contadores por status
        $totaisStatus = (clone $base)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total'      => (clone $base)->count(),
            'abertas'    => $totaisStatus['aberta']      ?? 0,
            'execucao'   => $totaisStatus['em_execucao'] ?? 0,
            'concluidas' => $totaisStatus['concluida']   ?? 0,
            'canceladas' => $totaisStatus['cancelada']   ?? 0,
        ];

        // lista paginada com order
        $ordens = OrdemServico::with(['setor', 'equipamento'])
            ->withCount(['tecnicos', 'gestores'])
            ->where('solicitante_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard-visualizador', [
            'stats'  => $stats,
            'ordens' => $ordens,
        ]);
    }
}
