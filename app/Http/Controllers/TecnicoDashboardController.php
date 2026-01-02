<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use Illuminate\Http\Request;

class TecnicoDashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Somente OS onde o usuÃ¡rio logado Ã© tÃ©cnico
        $queryBase = OrdemServico::with(['setor', 'equipamento', 'tecnicos'])
            ->doTecnico($userId);

        $osAbertas    = (clone $queryBase)->where('status', 'aberta')->count();
        $osExecucao   = (clone $queryBase)->where('status', 'em_execucao')->count();
        $osConcluidas = (clone $queryBase)->where('status', 'concluida')->count();

        $mostrarConcluidas = $request->boolean('mostrar_concluidas', false);

        $ordens = $queryBase
            ->when(!$mostrarConcluidas, fn ($q) => $q->where('status', '!=', 'concluida'))
            ->orderByRaw("FIELD(prioridade, 'muito_alto', 'alto', 'medio', 'baixo')")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('tecnico.dashboard', [
            'osAbertas'    => $osAbertas,
            'osExecucao'   => $osExecucao,
            'osConcluidas' => $osConcluidas,
            'mostrarConcluidas' => $mostrarConcluidas,
            'ordens'       => $ordens,
        ]);
    }
}
