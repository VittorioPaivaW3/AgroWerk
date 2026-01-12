<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório | OS por setor e equipamento</title>
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
            grid-template-columns: repeat(2, minmax(0, 1fr));
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
        .rank-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin: 0;
        }
        .card {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--card);
            padding: 12px 14px;
            box-shadow: var(--shadow);
        }
        .card h3 {
            margin: 0 0 10px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-top: 1px solid var(--border);
        }
        .item:first-child { border-top: none; }
        .item-title { font-weight: 600; font-size: 13px; color: var(--ink); }
        .item-sub { font-size: 11px; color: var(--muted); }
        .item-total { font-weight: 700; font-size: 15px; color: var(--ink); }
        @media print {
            body { background: #fff; }
            .page { padding: 0; }
            header,
            .pill,
            .card {
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
