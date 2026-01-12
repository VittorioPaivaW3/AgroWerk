<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\OrdemServico;
use App\Models\Setor;
use App\Models\User;
use App\Models\Projeto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function index()
    {
        $setores = Setor::orderBy('nome')->get(['id', 'nome']);
        $equipamentos = Equipamento::with('setor:id,nome')
            ->orderBy('nome')
            ->get(['id', 'nome', 'setor_id']);
        $tecnicos = User::role('tecnico')->orderBy('name')->get(['id', 'name']);

        $defaultInicio = now()->startOfMonth()->format('Y-m-d');
        $defaultFim = now()->format('Y-m-d');

        return view('relatorios.index', compact(
            'setores',
            'equipamentos',
            'tecnicos',
            'defaultInicio',
            'defaultFim'
        ));
    }

    public function osPorStatus(Request $request)
    {
        if (! $request->filled('inicio') || ! $request->filled('fim')) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Escolha um periodo para gerar o relatorio.');
        }

        $data = $request->validate([
            'inicio'         => ['required', 'date'],
            'fim'            => ['required', 'date'],
            'setor_id'       => ['nullable', 'integer', 'exists:setores,id'],
            'equipamento_id' => ['nullable', 'integer', 'exists:equipamentos,id'],
        ], [
            'inicio.required' => 'Informe a data inicial do periodo.',
            'fim.required'    => 'Informe a data final do periodo.',
        ]);

        $inicio = Carbon::parse($data['inicio'])->startOfDay();
        $fim = Carbon::parse($data['fim'])->endOfDay();

        if ($fim->lt($inicio)) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Data final deve ser maior ou igual a data inicial.');
        }

        $statusOrder = ['aberta', 'em_execucao', 'concluida', 'cancelada'];

        $query = OrdemServico::with([
                'setor:id,nome',
                'equipamento:id,nome,setor_id',
                'equipamento.setor:id,nome',
            ])
            ->when($data['setor_id'] ?? null, function ($q, $setorId) {
                $q->where('setor_id', $setorId);
            })
            ->when($data['equipamento_id'] ?? null, function ($q, $equipamentoId) {
                $q->where('equipamento_id', $equipamentoId);
            })
            ->whereBetween('created_at', [$inicio, $fim]);

        $statusCounts = (clone $query)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $ordens = $query
            ->orderBy('created_at')
            ->get()
            ->sortBy(function ($os) use ($statusOrder) {
                $statusPos = array_search($os->status, $statusOrder);
                $statusKey = $statusPos === false ? 99 : $statusPos;

                return sprintf(
                    '%02d-%s',
                    $statusKey,
                    $os->created_at?->format('YmdHis') ?? '00000000000000'
                );
            })
            ->values();

        $totalGeral = $ordens->count();

        $setorSelecionado = ! empty($data['setor_id'])
            ? Setor::select('id', 'nome')->find($data['setor_id'])
            : null;

        $equipamentoSelecionado = ! empty($data['equipamento_id'])
            ? Equipamento::with('setor:id,nome')
                ->select('id', 'nome', 'setor_id')
                ->find($data['equipamento_id'])
            : null;

        $statusLabels = [
            'aberta'      => 'Aberta',
            'em_execucao' => 'Em execucao',
            'concluida'   => 'Concluida',
            'cancelada'   => 'Cancelada',
        ];

        $viewData = [
            'ordens'                 => $ordens,
            'statusCounts'           => $statusCounts,
            'totalGeral'             => $totalGeral,
            'periodo'                => [
                'inicio' => $inicio,
                'fim'    => $fim,
            ],
            'statusLabels'           => $statusLabels,
            'statusOrder'            => $statusOrder,
            'setorSelecionado'       => $setorSelecionado,
            'equipamentoSelecionado' => $equipamentoSelecionado,
            'usuario'                => $request->user(),
        ];

        if ($request->boolean('print')) {
            return view('relatorios.os-por-status-print', $viewData);
        }

        return view('relatorios.os-por-status', $viewData);
    }

    public function tempoMedioAtendimento(Request $request)
    {
        if (! $request->filled('inicio') || ! $request->filled('fim')) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Escolha um periodo para gerar o relatorio.');
        }

        $data = $request->validate([
            'inicio'         => ['required', 'date'],
            'fim'            => ['required', 'date'],
            'setor_id'       => ['nullable', 'integer', 'exists:setores,id'],
            'equipamento_id' => ['nullable', 'integer', 'exists:equipamentos,id'],
            'tecnico_id'     => ['nullable', 'integer', 'exists:users,id'],
            'tipo'           => ['nullable', 'in:corretiva,preventiva'],
        ], [
            'inicio.required' => 'Informe a data inicial do periodo.',
            'fim.required'    => 'Informe a data final do periodo.',
        ]);

        $inicio = Carbon::parse($data['inicio'])->startOfDay();
        $fim = Carbon::parse($data['fim'])->endOfDay();

        if ($fim->lt($inicio)) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Data final deve ser maior ou igual a data inicial.');
        }

        $query = OrdemServico::with([
                'setor:id,nome',
                'equipamento:id,nome,setor_id',
                'equipamento.setor:id,nome',
                'tecnicos:id,name',
            ])
            ->when($data['setor_id'] ?? null, function ($q, $setorId) {
                $q->where('setor_id', $setorId);
            })
            ->when($data['equipamento_id'] ?? null, function ($q, $equipamentoId) {
                $q->where('equipamento_id', $equipamentoId);
            })
            ->when($data['tecnico_id'] ?? null, function ($q, $tecnicoId) {
                $q->whereHas('tecnicos', function ($sub) use ($tecnicoId) {
                    $sub->where('users.id', $tecnicoId);
                });
            })
            ->when($data['tipo'] ?? null, function ($q, $tipo) {
                $q->where('tipo', $tipo);
            })
            ->whereBetween('created_at', [$inicio, $fim]);

        $formatMinutes = function (?int $minutes) {
            if ($minutes === null) {
                return null;
            }

            $hours = intdiv($minutes, 60);
            $mins  = $minutes % 60;

            return sprintf('%02dh %02dmin', $hours, $mins);
        };

        $ordens = $query
            ->orderBy('created_at')
            ->get()
            ->map(function (OrdemServico $os) use ($formatMinutes) {
                $tempoAteInicioMin = ($os->created_at && $os->inicio_execucao_em)
                    ? $os->created_at->diffInMinutes($os->inicio_execucao_em)
                    : null;

                $tempoExecucaoMin = ($os->inicio_execucao_em && $os->fim_execucao_em)
                    ? $os->inicio_execucao_em->diffInMinutes($os->fim_execucao_em)
                    : null;

                $tempoTotalMin = ($os->created_at && $os->fim_execucao_em)
                    ? $os->created_at->diffInMinutes($os->fim_execucao_em)
                    : null;

                $os->tempo_ate_inicio_min = $tempoAteInicioMin;
                $os->tempo_execucao_min   = $tempoExecucaoMin;
                $os->tempo_total_min      = $tempoTotalMin;

                $os->tempo_ate_inicio_fmt = $formatMinutes($tempoAteInicioMin);
                $os->tempo_execucao_fmt   = $formatMinutes($tempoExecucaoMin);
                $os->tempo_total_fmt      = $formatMinutes($tempoTotalMin);

                return $os;
            });

        $mediaAteInicioMin = $ordens->whereNotNull('tempo_ate_inicio_min')->avg('tempo_ate_inicio_min');
        $mediaExecucaoMin  = $ordens->whereNotNull('tempo_execucao_min')->avg('tempo_execucao_min');
        $mediaTotalMin     = $ordens->whereNotNull('tempo_total_min')->avg('tempo_total_min');

        $medias = [
            'ate_inicio' => [
                'minutos'     => $mediaAteInicioMin,
                'formatado'   => $mediaAteInicioMin === null ? null : $formatMinutes((int) round($mediaAteInicioMin)),
                'consideradas'=> $ordens->whereNotNull('tempo_ate_inicio_min')->count(),
            ],
            'execucao' => [
                'minutos'     => $mediaExecucaoMin,
                'formatado'   => $mediaExecucaoMin === null ? null : $formatMinutes((int) round($mediaExecucaoMin)),
                'consideradas'=> $ordens->whereNotNull('tempo_execucao_min')->count(),
            ],
            'total' => [
                'minutos'     => $mediaTotalMin,
                'formatado'   => $mediaTotalMin === null ? null : $formatMinutes((int) round($mediaTotalMin)),
                'consideradas'=> $ordens->whereNotNull('tempo_total_min')->count(),
            ],
        ];

        $setorSelecionado = ! empty($data['setor_id'])
            ? Setor::select('id', 'nome')->find($data['setor_id'])
            : null;

        $equipamentoSelecionado = ! empty($data['equipamento_id'])
            ? Equipamento::with('setor:id,nome')
                ->select('id', 'nome', 'setor_id')
                ->find($data['equipamento_id'])
            : null;

        $tecnicoSelecionado = ! empty($data['tecnico_id'])
            ? User::select('id', 'name')->find($data['tecnico_id'])
            : null;

        $statusLabels = [
            'aberta'      => 'Aberta',
            'em_execucao' => 'Em execucao',
            'concluida'   => 'Concluida',
            'cancelada'   => 'Cancelada',
        ];

        $viewData = [
            'ordens'                 => $ordens,
            'medias'                 => $medias,
            'totalGeral'             => $ordens->count(),
            'periodo'                => [
                'inicio' => $inicio,
                'fim'    => $fim,
            ],
            'setorSelecionado'       => $setorSelecionado,
            'equipamentoSelecionado' => $equipamentoSelecionado,
            'tecnicoSelecionado'     => $tecnicoSelecionado,
            'tipoSelecionado'        => $data['tipo'] ?? null,
            'statusLabels'           => $statusLabels,
            'usuario'                => $request->user(),
        ];

        if ($request->boolean('print')) {
            return view('relatorios.tempo-medio-atendimento-print', $viewData);
        }

        return view('relatorios.tempo-medio-atendimento', $viewData);
    }

    public function custoOsPeriodo(Request $request)
    {
        if (! $request->filled('inicio') || ! $request->filled('fim')) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Escolha um periodo para gerar o relatorio.');
        }

        $data = $request->validate([
            'inicio'         => ['required', 'date'],
            'fim'            => ['required', 'date'],
            'setor_id'       => ['nullable', 'integer', 'exists:setores,id'],
            'equipamento_id' => ['nullable', 'integer', 'exists:equipamentos,id'],
            'tipo'           => ['nullable', 'in:corretiva,preventiva'],
            'status'         => ['nullable', 'in:aberta,em_execucao,concluida,cancelada'],
        ], [
            'inicio.required' => 'Informe a data inicial do periodo.',
            'fim.required'    => 'Informe a data final do periodo.',
        ]);

        $inicio = Carbon::parse($data['inicio'])->startOfDay();
        $fim = Carbon::parse($data['fim'])->endOfDay();

        if ($fim->lt($inicio)) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Data final deve ser maior ou igual a data inicial.');
        }

        $statusFiltro = $data['status'] ?? 'concluida';

        $query = OrdemServico::with([
                'setor:id,nome',
                'equipamento:id,nome,setor_id',
                'equipamento.setor:id,nome',
                'tecnicos:id,valor_hora',
            ])
            ->when($data['setor_id'] ?? null, function ($q, $setorId) {
                $q->where('setor_id', $setorId);
            })
            ->when($data['equipamento_id'] ?? null, function ($q, $equipamentoId) {
                $q->where('equipamento_id', $equipamentoId);
            })
            ->when($data['tipo'] ?? null, function ($q, $tipo) {
                $q->where('tipo', $tipo);
            })
            ->when($statusFiltro, function ($q, $statusFiltro) {
                $q->where('status', $statusFiltro);
            })
            ->whereBetween('created_at', [$inicio, $fim]);

        $ordens = $query
            ->orderBy('created_at')
            ->get()
            ->map(function (OrdemServico $os) {
                $maoObra = $os->custo_mao_de_obra ?? 0;
                $custoMaterial = (float) ($os->custo_total ?? 0);

                $os->custo_mao_obra_calc = $maoObra;
                $os->custo_material_calc = $custoMaterial;
                $os->custo_total_com_mao = $custoMaterial + $maoObra;

                return $os;
            });

        $comCustoMaterial = $ordens->filter(fn ($os) => $os->custo_material_calc > 0);
        $comCustoTotal    = $ordens->filter(fn ($os) => $os->custo_total_com_mao > 0);

        $totalCustoMaterial = $ordens->sum('custo_material_calc');
        $totalCustoMaoObra  = $ordens->sum('custo_mao_obra_calc');
        $totalCustoGeral    = $ordens->sum('custo_total_com_mao');

        $custoMedioMaterial = $comCustoMaterial->count()
            ? $totalCustoMaterial / $comCustoMaterial->count()
            : null;

        $custoMedioGeral = $comCustoTotal->count()
            ? $totalCustoGeral / $comCustoTotal->count()
            : null;

        $statusLabels = [
            'aberta'      => 'Aberta',
            'em_execucao' => 'Em execucao',
            'concluida'   => 'Concluida',
            'cancelada'   => 'Cancelada',
        ];

        $setorSelecionado = ! empty($data['setor_id'])
            ? Setor::select('id', 'nome')->find($data['setor_id'])
            : null;

        $equipamentoSelecionado = ! empty($data['equipamento_id'])
            ? Equipamento::with('setor:id,nome')
                ->select('id', 'nome', 'setor_id')
                ->find($data['equipamento_id'])
            : null;

        $viewData = [
            'ordens'                 => $ordens,
            'totalGeral'             => $ordens->count(),
            'periodo'                => [
                'inicio' => $inicio,
                'fim'    => $fim,
            ],
            'setorSelecionado'       => $setorSelecionado,
            'equipamentoSelecionado' => $equipamentoSelecionado,
            'tipoSelecionado'        => $data['tipo'] ?? null,
            'statusSelecionado'      => $statusFiltro,
            'statusLabels'           => $statusLabels,
            'totalCustoMaterial'     => $totalCustoMaterial,
            'totalCustoMaoObra'      => $totalCustoMaoObra,
            'totalCustoGeral'        => $totalCustoGeral,
            'custoMedioMaterial'     => $custoMedioMaterial,
            'custoMedioGeral'        => $custoMedioGeral,
            'comCustoMaterialCount'  => $comCustoMaterial->count(),
            'comCustoTotalCount'     => $comCustoTotal->count(),
            'usuario'                => $request->user(),
        ];

        if ($request->boolean('print')) {
            return view('relatorios.custo-os-print', $viewData);
        }

        return view('relatorios.custo-os', $viewData);
    }

    public function osPorSetorEquipamento(Request $request)
    {
        if (! $request->filled('inicio') || ! $request->filled('fim')) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Escolha um periodo para gerar o relatorio.');
        }

        $data = $request->validate([
            'inicio'         => ['required', 'date'],
            'fim'            => ['required', 'date'],
            'tipo'           => ['nullable', 'in:corretiva,preventiva'],
            'status'         => ['nullable', 'in:aberta,em_execucao,concluida,cancelada'],
        ], [
            'inicio.required' => 'Informe a data inicial do periodo.',
            'fim.required'    => 'Informe a data final do periodo.',
        ]);

        $inicio = Carbon::parse($data['inicio'])->startOfDay();
        $fim = Carbon::parse($data['fim'])->endOfDay();

        if ($fim->lt($inicio)) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Data final deve ser maior ou igual a data inicial.');
        }

        $baseQuery = OrdemServico::query()
            ->whereBetween('created_at', [$inicio, $fim])
            ->when($data['tipo'] ?? null, fn ($q, $tipo) => $q->where('tipo', $tipo))
            ->when($data['status'] ?? null, fn ($q, $status) => $q->where('status', $status));

        $rankingSetores = (clone $baseQuery)
            ->selectRaw('setor_id, COUNT(*) as total')
            ->groupBy('setor_id')
            ->get()
            ->loadMissing('setor:id,nome')
            ->sortByDesc('total')
            ->values();

        $rankingEquip = (clone $baseQuery)
            ->selectRaw('equipamento_id, COUNT(*) as total')
            ->groupBy('equipamento_id')
            ->get()
            ->loadMissing('equipamento:id,nome,setor_id', 'equipamento.setor:id,nome')
            ->sortByDesc('total')
            ->values();

        $statusLabels = [
            'aberta'      => 'Aberta',
            'em_execucao' => 'Em execucao',
            'concluida'   => 'Concluida',
            'cancelada'   => 'Cancelada',
        ];

        $viewData = [
            'rankingSetores'         => $rankingSetores,
            'rankingEquipamentos'    => $rankingEquip,
            'totalGeral'             => $baseQuery->count(),
            'periodo'                => [
                'inicio' => $inicio,
                'fim'    => $fim,
            ],
            'tipoSelecionado'        => $data['tipo'] ?? null,
            'statusSelecionado'      => $data['status'] ?? null,
            'statusLabels'           => $statusLabels,
            'usuario'                => $request->user(),
        ];

        if ($request->boolean('print')) {
            return view('relatorios.os-por-setor-equip-print', $viewData);
        }

        return view('relatorios.os-por-setor-equip', $viewData);
    }

    public function produtividadeTecnico(Request $request)
    {
        if (! $request->filled('inicio') || ! $request->filled('fim')) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Escolha um periodo para gerar o relatorio.');
        }

        $data = $request->validate([
            'inicio'   => ['required', 'date'],
            'fim'      => ['required', 'date'],
            'setor_id' => ['nullable', 'integer', 'exists:setores,id'],
            'tipo'     => ['nullable', 'in:corretiva,preventiva'],
            'status'   => ['nullable', 'in:aberta,em_execucao,concluida,cancelada'],
        ], [
            'inicio.required' => 'Informe a data inicial do periodo.',
            'fim.required'    => 'Informe a data final do periodo.',
        ]);

        $inicio = Carbon::parse($data['inicio'])->startOfDay();
        $fim = Carbon::parse($data['fim'])->endOfDay();

        if ($fim->lt($inicio)) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Data final deve ser maior ou igual a data inicial.');
        }

        $ordens = OrdemServico::with([
                'setor:id,nome',
                'equipamento:id,nome,setor_id',
                'equipamento.setor:id,nome',
                'tecnicos:id,name,valor_hora',
            ])
            ->when($data['setor_id'] ?? null, fn ($q, $setorId) => $q->where('setor_id', $setorId))
            ->when($data['tipo'] ?? null, fn ($q, $tipo) => $q->where('tipo', $tipo))
            ->when($data['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->whereBetween('created_at', [$inicio, $fim])
            ->get();

        $produtividade = [];

        foreach ($ordens as $os) {
            $duracaoHoras = $os->duracao_execucao_em_horas ?? null;

            foreach ($os->tecnicos as $tec) {
                $id = $tec->id;

                if (! isset($produtividade[$id])) {
                    $produtividade[$id] = [
                        'id'                 => $id,
                        'nome'               => $tec->name,
                        'valor_hora'         => $tec->valor_hora ?? 0,
                        'total_os'           => 0,
                        'os_concluidas'      => 0,
                        'horas_execucao'     => 0,
                        'custo_mao_obra'     => 0,
                    ];
                }

                $produtividade[$id]['total_os']++;

                if (($os->status ?? null) === 'concluida') {
                    $produtividade[$id]['os_concluidas']++;
                }

                if ($duracaoHoras !== null) {
                    $produtividade[$id]['horas_execucao'] += $duracaoHoras;
                    $produtividade[$id]['custo_mao_obra'] += ($tec->valor_hora ?? 0) * $duracaoHoras;
                }
            }
        }

        $ranking = collect($produtividade)
            ->sortByDesc('total_os')
            ->values();

        $totalHoras = $ranking->sum('horas_execucao');
        $totalCustoMao = $ranking->sum('custo_mao_obra');

        $statusLabels = [
            'aberta'      => 'Aberta',
            'em_execucao' => 'Em execucao',
            'concluida'   => 'Concluida',
            'cancelada'   => 'Cancelada',
        ];

        $setorSelecionado = ! empty($data['setor_id'])
            ? Setor::select('id', 'nome')->find($data['setor_id'])
            : null;

        $viewData = [
            'ranking'             => $ranking,
            'totalGeral'          => $ordens->count(),
            'periodo'             => [
                'inicio' => $inicio,
                'fim'    => $fim,
            ],
            'setorSelecionado'    => $setorSelecionado,
            'tipoSelecionado'     => $data['tipo'] ?? null,
            'statusSelecionado'   => $data['status'] ?? null,
            'statusLabels'        => $statusLabels,
            'totalHoras'          => $totalHoras,
            'totalCustoMao'       => $totalCustoMao,
            'usuario'             => $request->user(),
        ];

        if ($request->boolean('print')) {
            return view('relatorios.produtividade-tecnico-print', $viewData);
        }

        return view('relatorios.produtividade-tecnico', $viewData);
    }

    public function projetosInvestimentos(Request $request)
    {
        if (! $request->filled('inicio') || ! $request->filled('fim')) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Escolha um periodo para gerar o relatorio.');
        }

        $data = $request->validate([
            'inicio'   => ['required', 'date'],
            'fim'      => ['required', 'date'],
            'setor_id' => ['nullable', 'integer', 'exists:setores,id'],
            'status'   => ['nullable', 'in:aberto,em_andamento,concluido,cancelado'],
        ], [
            'inicio.required' => 'Informe a data inicial do periodo.',
            'fim.required'    => 'Informe a data final do periodo.',
        ]);

        $inicio = Carbon::parse($data['inicio'])->startOfDay();
        $fim = Carbon::parse($data['fim'])->endOfDay();

        if ($fim->lt($inicio)) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Data final deve ser maior ou igual a data inicial.');
        }

        $query = Projeto::with('setor:id,nome')
            ->when($data['setor_id'] ?? null, fn ($q, $setorId) => $q->where('setores_id', $setorId))
            ->when($data['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->whereBetween('created_at', [$inicio, $fim]);

        $projetos = $query
            ->orderBy('created_at')
            ->get()
            ->map(function (Projeto $proj) {
                $prev = (float) ($proj->orcamento_previsto ?? 0);
                $real = (float) ($proj->orcamento_real ?? 0);
                $proj->orcamento_previsto_calc = $prev;
                $proj->orcamento_real_calc     = $real;
                $proj->variacao_calc           = $real - $prev;
                return $proj;
            });

        $totalPrevisto = $projetos->sum('orcamento_previsto_calc');
        $totalReal     = $projetos->sum('orcamento_real_calc');
        $totalVariacao = $projetos->sum('variacao_calc');

        $statusLabels = [
            'aberto'       => 'Aberto',
            'em_andamento' => 'Em andamento',
            'concluido'    => 'Concluido',
            'cancelado'    => 'Cancelado',
        ];

        $setorSelecionado = ! empty($data['setor_id'])
            ? Setor::select('id', 'nome')->find($data['setor_id'])
            : null;

        $viewData = [
            'projetos'          => $projetos,
            'totalProjetos'     => $projetos->count(),
            'totalPrevisto'     => $totalPrevisto,
            'totalReal'         => $totalReal,
            'totalVariacao'     => $totalVariacao,
            'periodo'           => [
                'inicio' => $inicio,
                'fim'    => $fim,
            ],
            'setorSelecionado'  => $setorSelecionado,
            'statusSelecionado' => $data['status'] ?? null,
            'statusLabels'      => $statusLabels,
            'usuario'           => $request->user(),
        ];

        if ($request->boolean('print')) {
            return view('relatorios.projetos-investimentos-print', $viewData);
        }

        return view('relatorios.projetos-investimentos', $viewData);
    }
}
