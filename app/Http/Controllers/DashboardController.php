<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $hoje = now()->toDateString();

        // Filtros
        $filters = $request->only(['inicio', 'fim', 'setor_id', 'tecnico_id', 'status']);

        // Query base com filtros
        $base = OrdemServico::query();

        if (!empty($filters['inicio'])) {
            $base->whereDate('created_at', '>=', $filters['inicio']);
        }
        if (!empty($filters['fim'])) {
            $base->whereDate('created_at', '<=', $filters['fim']);
        }
        if (!empty($filters['setor_id'])) {
            $base->where('setor_id', $filters['setor_id']);
        }
        if (!empty($filters['status'])) {
            $base->where('status', $filters['status']);
        }
        if (!empty($filters['tecnico_id'])) {
            $base->whereHas('tecnicos', function ($q) use ($filters) {
                $q->where('users.id', $filters['tecnico_id']);
            });
        }

        // Contagem por status para gráficos
        $statusCounts = (clone $base)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Últimos 6 meses de criação de OS (YYYY-MM)
        $mensal = (clone $base)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->take(6)
            ->get()
            ->map(function ($row) {
                return [
                    'mes' => $row->mes,
                    'label' => \Carbon\Carbon::createFromFormat('Y-m', $row->mes)->translatedFormat('M/Y'),
                    'total' => (int) $row->total,
                ];
            });

        // Contagem por setor
        $setorCounts = (clone $base)
            ->leftJoin('setores', 'ordem_servicos.setor_id', '=', 'setores.id')
            ->selectRaw("COALESCE(setores.nome, 'Sem setor') as setor, COUNT(*) as total")
            ->groupBy('setor')
            ->orderByDesc('total')
            ->get();

        // Contagem por técnico atribuído
        $tecnicoCounts = (clone $base)
            ->leftJoin('ordem_servico_user', function($join) {
                $join->on('ordem_servicos.id', '=', 'ordem_servico_user.ordem_servico_id')
                     ->where('ordem_servico_user.papel', '=', 'tecnico');
            })
            ->leftJoin('users', 'users.id', '=', 'ordem_servico_user.user_id')
            ->selectRaw("COALESCE(users.name, 'Sem técnico') as tecnico, COUNT(DISTINCT ordem_servicos.id) as total")
            ->groupBy('tecnico')
            ->orderByDesc('total')
            ->get();

        // Contagem por tipo (corretiva/preventiva)
        $tipoCounts = (clone $base)
            ->selectRaw('COALESCE(tipo, "Sem tipo") as tipo, COUNT(*) as total')
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();

        return view('dashboard', [
            // Indicadores principais
            'totalOrdens'       => OrdemServico::count(),
            'osAbertas'         => OrdemServico::where('status', 'aberta')->count(),
            'osEmExecucao'      => OrdemServico::where('status', 'em_execucao')->count(),
            'osConcluidasHoje'  => OrdemServico::where('status', 'concluida')
                                        ->whereDate('updated_at', $hoje)
                                        ->count(),
            'osCriadasHoje'     => OrdemServico::whereDate('created_at', $hoje)->count(),

        // Últimas OS (com relações atuais)
        'ordens'            => (clone $base)
                                    ->with(['solicitante', 'setor', 'equipamento'])
                                    ->latest()
                                    ->paginate(10)
                                    ->withQueryString(),

            // Dados para gráficos
        'statusCounts'      => $statusCounts,
        'mensalSeries'      => $mensal,
        'setorCounts'       => $setorCounts,
        'tecnicoCounts'     => $tecnicoCounts,
        'tipoCounts'        => $tipoCounts,
        'filters'           => $filters,
        'setores'           => Setor::orderBy('nome')->get(),
        'tecnicos'          => User::role('tecnico')->orderBy('name')->get(['id','name']),
    ]);
}
}
