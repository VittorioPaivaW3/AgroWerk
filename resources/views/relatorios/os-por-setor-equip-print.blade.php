<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório | OS por setor e equipamento</title>
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
        .meta strong { color: var(--ink); }
        .pill-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
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
        .rank-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 10px;
        }
        .card {
            border: 1px solid var(--border);
            border-radius: 6px;
            background: #fff;
            padding: 10px 12px;
        }
        .card h3 {
            margin: 0 0 8px;
            font-size: 15px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-top: 1px solid var(--border);
        }
        .item:first-child { border-top: none; }
        .item-title { font-weight: 600; font-size: 13px; color: var(--ink); }
        .item-sub { font-size: 11px; color: var(--muted); }
        .item-total { font-weight: 700; font-size: 15px; color: var(--ink); }
        @media print {
            body { background: #fff; }
            .page { border: none; border-radius: 0; }
        }
    </style>
</head>
<body>
    <div class="page">
        <header>
            <div style="display:flex; align-items:center; gap:12px;">
                <img src="{{ asset('imagem/Logo-AgroWerk.svg') }}" alt="Logo" style="height:52px; width:auto;">
                <div class="title">
                    <h1>Relatório de OS por setor e equipamento</h1>
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

        <div class="rank-grid">
            <div class="card">
                <h3>Ranking de setores</h3>
                @forelse($rankingSetores as $index => $item)
                    <div class="item">
                        <div>
                            <div class="item-title">{{ $item->setor->nome ?? 'Sem setor' }}</div>
                            <div class="item-sub">Posição {{ $index + 1 }}º</div>
                        </div>
                        <div class="item-total">{{ $item->total }}</div>
                    </div>
                @empty
                    <p class="item-sub" style="padding:6px 0;">Nenhuma OS no período.</p>
                @endforelse
            </div>
            <div class="card">
                <h3>Ranking de equipamentos</h3>
                @forelse($rankingEquipamentos as $index => $item)
                    <div class="item">
                        <div>
                            <div class="item-title">{{ $item->equipamento->nome ?? 'Sem equipamento' }}</div>
                            <div class="item-sub">Setor: {{ $item->equipamento->setor->nome ?? '-' }} | Posição {{ $index + 1 }}º</div>
                        </div>
                        <div class="item-total">{{ $item->total }}</div>
                    </div>
                @empty
                    <p class="item-sub" style="padding:6px 0;">Nenhuma OS no período.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
