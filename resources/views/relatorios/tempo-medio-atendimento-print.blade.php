<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório | Tempo médio de atendimento</title>
    <style>
        :root {
            --ink: #111827;
            --muted: #4b5563;
            --border: #e5e7eb;
            --bg: #f7f9fc;
        }
        @page {
            size: A4 landscape;
            margin: 12mm;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Helvetica Neue", Arial, sans-serif;
            color: var(--ink);
            background: var(--bg);
        }
        .page {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 16px 20px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--ink);
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .title h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 0.2px;
        }
        .title p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 12px;
        }
        .meta {
            text-align: right;
            font-size: 12px;
            color: var(--muted);
        }
        .meta strong {
            color: var(--ink);
        }
        .pill-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 14px;
        }
        .pill {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 8px 10px;
            background: #fff;
        }
        .pill .label {
            text-transform: uppercase;
            font-size: 10px;
            color: var(--muted);
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .pill .value {
            font-weight: 700;
            font-size: 14px;
            color: var(--ink);
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }
        .kpi {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 12px;
            background: #fff;
        }
        .kpi .kpi-title {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
        }
        .kpi .kpi-number {
            margin: 8px 0 4px;
            font-size: 22px;
            font-weight: 700;
        }
        .kpi .kpi-foot {
            font-size: 11px;
            color: var(--muted);
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 12px;
        }
        th, td {
            padding: 8px 10px;
            border: 1px solid var(--border);
        }
        th {
            text-align: left;
            background: #eef2f7;
            font-size: 11px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        td.desc {
            max-width: 320px;
        }
        @media print {
            body {
                background: #fff;
            }
            .page {
                border: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <header>
            <div style="display:flex; align-items:center; gap:12px;">
                <img src="{{ asset('logo/logo.png') }}" alt="Logo" style="height:42px; width:auto;">
                <div class="title">
                    <h1>Relatório de tempo médio de atendimento</h1>
                    <p>Intervalo {{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}</p>
                </div>
            </div>
            <div class="meta">
                <div><strong>Total de OS:</strong> {{ $totalGeral }}</div>
                <div>Gerado em {{ now()->format('d/m/Y H:i') }}</div>
                <div>Usuário: {{ $usuario?->name ?? '—' }}</div>
            </div>
        </header>

        <div class="pill-grid">
            <div class="pill">
                <div class="label">Setor</div>
                <div class="value">{{ $setorSelecionado->nome ?? 'Todos os setores' }}</div>
            </div>
            <div class="pill">
                <div class="label">Equipamento</div>
                <div class="value">
                    {{ $equipamentoSelecionado->nome ?? 'Todos os equipamentos' }}
                    @if($equipamentoSelecionado?->setor?->nome)
                        <span style="color: var(--muted); font-size: 11px;">(Setor: {{ $equipamentoSelecionado->setor->nome }})</span>
                    @endif
                </div>
            </div>
            <div class="pill">
                <div class="label">Técnico</div>
                <div class="value">{{ $tecnicoSelecionado->name ?? 'Todos os técnicos' }}</div>
            </div>
            <div class="pill">
                <div class="label">Tipo</div>
                <div class="value">
                    @if($tipoSelecionado === 'corretiva') Corretiva
                    @elseif($tipoSelecionado === 'preventiva') Preventiva
                    @else Todos @endif
                </div>
            </div>
        </div>

        <div class="kpi-grid">
            @php
                $cards = [
                    ['titulo' => 'Até início da execução', 'dados' => $medias['ate_inicio'] ?? null],
                    ['titulo' => 'Execução', 'dados' => $medias['execucao'] ?? null],
                    ['titulo' => 'Ciclo completo', 'dados' => $medias['total'] ?? null],
                ];
            @endphp
            @foreach($cards as $card)
                @php
                    $valor = $card['dados']['formatado'] ?? null;
                    $consideradas = $card['dados']['consideradas'] ?? 0;
                @endphp
                <div class="kpi">
                    <p class="kpi-title">{{ $card['titulo'] }}</p>
                    <p class="kpi-number">{{ $valor ?? 'Sem dados' }}</p>
                    <p class="kpi-foot">{{ $consideradas }} OS consideradas</p>
                </div>
            @endforeach
        </div>

        <h3 style="margin: 12px 0 8px; font-size: 15px; letter-spacing: 0.2px;">Detalhamento das OS</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 7%;">Código</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 18%;">Setor</th>
                    <th style="width: 16%;">Equipamento</th>
                    <th style="width: 16%;">Técnicos</th>
                    <th style="width: 11%;">Criada em</th>
                    <th style="width: 11%;">Início exec.</th>
                    <th style="width: 11%;">Conclusão</th>
                    <th style="width: 10%;">Até início</th>
                    <th style="width: 10%;">Execução</th>
                    <th style="width: 10%;">Ciclo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordens as $ordem)
                    @php
                        $setorNome = $ordem->setor->nome
                            ?? $ordem->equipamento->setor->nome
                            ?? '-';
                        $tecnicos = $ordem->tecnicos->pluck('name')->join(', ');
                    @endphp
                    <tr>
                        <td>#{{ $ordem->codigo ?? $ordem->id }}</td>
                        <td>{{ $statusLabels[$ordem->status] ?? $ordem->status }}</td>
                        <td>{{ $setorNome }}</td>
                        <td>{{ $ordem->equipamento->nome ?? '-' }}</td>
                        <td>{{ $tecnicos ?: '-' }}</td>
                        <td>{{ $ordem->created_at?->format('d/m/Y H:i') }}</td>
                        <td>{{ $ordem->inicio_execucao_em?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $ordem->fim_execucao_em?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $ordem->tempo_ate_inicio_fmt ?? '-' }}</td>
                        <td>{{ $ordem->tempo_execucao_fmt ?? '-' }}</td>
                        <td>{{ $ordem->tempo_total_fmt ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align:center; color: var(--muted); padding: 14px;">
                            Nenhuma OS encontrada para o período selecionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
