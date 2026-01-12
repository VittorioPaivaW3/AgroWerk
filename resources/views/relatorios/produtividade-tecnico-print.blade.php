<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório | Produtividade por técnico</title>
    <style>
        :root {
            --ink: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f3f4f6;
            --card: #ffffff;
            --accent: #8DC63F;
            --shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
        }
        @page {
            size: A4 landscape;
            margin: 12mm;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Josefin Sans", "Figtree", "Helvetica Neue", Arial, sans-serif;
            color: var(--ink);
            background: var(--bg);
        }
        .page {
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px 16px;
            box-shadow: var(--shadow);
        }
        .title h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
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
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .meta strong { color: var(--ink); }
        .pill-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin: 0;
        }
        .pill {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 12px;
            background: #f9fafb;
        }
        .pill .label {
            text-transform: uppercase;
            font-size: 10px;
            color: var(--muted);
            letter-spacing: 0.08em;
            margin-bottom: 4px;
            font-weight: 600;
        }
        .pill .value {
            font-weight: 600;
            font-size: 14px;
            color: var(--ink);
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin: 0;
        }
        .kpi {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 14px;
            background: var(--card);
            box-shadow: var(--shadow);
        }
        .kpi .kpi-title {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            color: var(--ink);
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
        .table-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .table-head {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 12px;
            padding: 12px 16px 8px;
            border-bottom: 1px solid var(--border);
        }
        .table-head h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .table-wrap {
            overflow: hidden;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .report-table th,
        .report-table td {
            padding: 8px 10px;
            border-bottom: 1px solid var(--border);
        }
        .report-table th {
            text-align: left;
            background: #f8fafc;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .report-table tbody tr:last-child td {
            border-bottom: none;
        }
        @media print {
            body { background: #fff; }
            .page { padding: 0; }
            header,
            .pill,
            .kpi,
            .table-card {
                box-shadow: none;
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
                    <h1>Relatório de produtividade por técnico</h1>
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
                <div class="label">Tipo</div>
                <div class="value">
                    @if($tipoSelecionado === 'corretiva') Corretiva
                    @elseif($tipoSelecionado === 'preventiva') Preventiva
                    @else Todos @endif
                </div>
            </div>
            <div class="pill">
                <div class="label">Status</div>
                <div class="value">{{ $statusLabels[$statusSelecionado] ?? ($statusSelecionado ? $statusSelecionado : 'Todos') }}</div>
            </div>
        </div>

        @php
            $formatHours = function (?float $hours) {
                if ($hours === null) {
                    return null;
                }
                $h = floor($hours);
                $m = round(($hours - $h) * 60);
                return sprintf('%02dh %02dmin', $h, $m);
            };
            $formatMoney = function (?float $value) {
                if ($value === null) return null;
                return 'R$ ' . number_format($value, 2, ',', '.');
            };
        @endphp

        <div class="kpi-grid">
            <div class="kpi">
                <p class="kpi-title">Horas de execução</p>
                <p class="kpi-number">{{ $formatHours($totalHoras) ?? 'Sem dados' }}</p>
                <p class="kpi-foot">Somente OS com início e fim</p>
            </div>
            <div class="kpi">
                <p class="kpi-title">Custo de mão de obra</p>
                <p class="kpi-number">{{ $formatMoney($totalCustoMao) ?? 'Sem dados' }}</p>
                <p class="kpi-foot">Valor_hora de cada técnico</p>
            </div>
            <div class="kpi">
                <p class="kpi-title">Quantidade de OS</p>
                <p class="kpi-number">{{ $totalGeral }}</p>
                <p class="kpi-foot">No período filtrado</p>
            </div>
        </div>
        <div class="table-card">
            <div class="table-head">
                <h3>Ranking de técnicos</h3>
            </div>
            <div class="table-wrap">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">Posição</th>
                            <th style="width: 26%;">Técnico</th>
                            <th style="width: 10%;">OS</th>
                            <th style="width: 12%;">OS concl.</th>
                            <th style="width: 12%;">Horas</th>
                            <th style="width: 16%;">Custo mão de obra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ranking as $index => $tec)
                            <tr>
                                <td>{{ $index + 1 }}º</td>
                                <td>{{ $tec['nome'] }}</td>
                                <td>{{ $tec['total_os'] }}</td>
                                <td>{{ $tec['os_concluidas'] }}</td>
                                <td>{{ $formatHours($tec['horas_execucao']) ?? '-' }}</td>
                                <td>{{ $formatMoney($tec['custo_mao_obra']) ?? '-' }}</td>
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
        </div>
    </div>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>




