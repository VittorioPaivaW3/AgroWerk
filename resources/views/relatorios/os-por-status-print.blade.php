@php
    use Illuminate\Support\Str;

    $statusOrder = $statusOrder ?? ['aberta', 'em_execucao', 'concluida', 'cancelada'];
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório | OS por status e período</title>
    <style>
        :root {
            --ink: #111827;
            --muted: #4b5563;
            --border: #e5e7eb;
            --accent: #0f766e;
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
            grid-template-columns: repeat(3, minmax(0, 1fr));
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
            grid-template-columns: repeat(4, minmax(0, 1fr));
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
            max-width: 340px;
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
                <img src="{{ asset('imagem/Logo-AgroWerk.svg') }}" alt="Logo" style="height:52px; width:auto;">
                <div class="title">
                    <h1>Relatório de OS por status</h1>
                    <p>Consolidado por período, setor e equipamento</p>
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
                <div class="label">Período</div>
                <div class="value">{{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}</div>
            </div>
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
        </div>

        <div class="kpi-grid">
            @foreach($statusOrder as $statusKey)
                @php
                    $count = (int)($statusCounts[$statusKey] ?? 0);
                    $label = $statusLabels[$statusKey] ?? Str::title(str_replace('_', ' ', $statusKey));
                    $percent = $totalGeral > 0 ? round(($count / $totalGeral) * 100) : 0;
                @endphp
                <div class="kpi">
                    <p class="kpi-title">{{ $label }}</p>
                    <p class="kpi-number">{{ number_format($count, 0, ',', '.') }}</p>
                    <p class="kpi-foot">{{ $percent }}% do período</p>
                </div>
            @endforeach
        </div>

        <h3 style="margin: 12px 0 8px; font-size: 15px; letter-spacing: 0.2px;">Detalhamento das OS</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Código</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 20%;">Setor</th>
                    <th style="width: 18%;">Equipamento</th>
                    <th style="width: 14%;">Criada em</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordens as $ordem)
                    @php
                        $statusKey = $ordem->status ?? '';
                        $statusLabel = $statusLabels[$statusKey] ?? Str::title(str_replace('_', ' ', $statusKey));
                        $setorNome = $ordem->setor->nome
                            ?? $ordem->equipamento->setor->nome
                            ?? '-';
                        $descricaoCurta = $ordem->descricao
                            ? Str::limit(strip_tags($ordem->descricao), 160)
                            : '-';
                    @endphp
                    <tr>
                        <td>#{{ $ordem->codigo ?? $ordem->id }}</td>
                        <td>{{ $statusLabel }}</td>
                        <td>{{ $setorNome }}</td>
                        <td>{{ $ordem->equipamento->nome ?? '-' }}</td>
                        <td>{{ $ordem->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="desc">{{ $descricaoCurta }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color: var(--muted); padding: 14px;">
                            Nenhuma OS encontrada para o período selecionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        // Facilita para quem clicou em "Imprimir" abrir já com diálogo
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
