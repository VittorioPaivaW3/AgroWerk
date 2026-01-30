<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Meu painel') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Visao geral das suas ordens de servico.
                </p>
            </div>
        </div>
    </x-slot>

    @php
        $stats = $stats ?? [];
        $totalOS = $stats['total']
            ?? (($stats['abertas'] ?? 0)
            + ($stats['execucao'] ?? 0)
            + ($stats['concluidas'] ?? 0)
            + ($stats['canceladas'] ?? 0));
    @endphp

    <div class="relative py-8">
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ===== KPI Topo ===== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total --}}
                <div class="kpi-card animate-in" style="animation-delay: 60ms">
                    <div class="kpi-topline">
                        <p class="kpi-label">Total de OS</p>
                        <span class="kpi-pill">Minhas OS</span>
                    </div>
                    <p class="kpi-value">{{ $totalOS ?? 0 }}</p>
                </div>

                {{-- Abertas --}}
                <div class="kpi-card animate-in" style="animation-delay: 120ms">
                    <div class="kpi-topline">
                        <p class="kpi-label">Abertas</p>
                        <span class="inline-flex items-center">
                            <img src="{{ asset('imagem/engrenagem_alerta.png') }}"
                                 alt="Engrenagem alerta"
                                 class="h-8 w-8 object-contain opacity-90 dark:hidden">
                            <img src="{{ asset('imagem/engrenagem_alerta_white.png') }}"
                                 alt="Engrenagem alerta branca"
                                 class="hidden h-8 w-8 object-contain opacity-90 dark:block">
                        </span>
                    </div>
                    <p class="kpi-value">{{ $stats['abertas'] ?? 0 }}</p>
                    <p class="kpi-sub">Aguardando atendimento</p>
                </div>

                {{-- Em execucao --}}
                <div class="kpi-card animate-in" style="animation-delay: 180ms">
                    <div class="kpi-topline">
                        <p class="kpi-label">Em execucao</p>
                        <span class="inline-flex items-center">
                            <img src="{{ asset('imagem/engrenagem_play.png') }}"
                                 alt="Engrenagem em execucao"
                                 class="h-8 w-8 object-contain opacity-90 dark:hidden">
                            <img src="{{ asset('imagem/engrenagem_play_white.png') }}"
                                 alt="Engrenagem em execucao branca"
                                 class="hidden h-8 w-8 object-contain opacity-90 dark:block">
                        </span>
                    </div>
                    <p class="kpi-value">{{ $stats['execucao'] ?? 0 }}</p>
                    <p class="kpi-sub">Em andamento</p>
                </div>

                {{-- Concluidas --}}
                <div class="kpi-card animate-in" style="animation-delay: 240ms">
                    <div class="kpi-topline">
                        <p class="kpi-label">Concluidas</p>
                        <span class="inline-flex items-center">
                            <img src="{{ asset('imagem/engrenagem.png') }}"
                                 alt="Engrenagem concluida"
                                 class="h-8 w-8 object-contain opacity-90 dark:hidden">
                            <img src="{{ asset('imagem/engrenagem_white.png') }}"
                                 alt="Engrenagem concluida branca"
                                 class="hidden h-8 w-8 object-contain opacity-90 dark:block">
                        </span>
                    </div>
                    <p class="kpi-value">{{ $stats['concluidas'] ?? 0 }}</p>
                    <p class="kpi-sub">Finalizadas</p>
                </div>
            </div>

            {{-- ===== Tabela minhas OS ===== --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                        dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>

                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                            Minhas solicitacoes de OS
                        </h3>
                        <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-300">
                            Acompanhe suas OS e priorize pelo status e urgencia.
                        </p>
                    </div>

                    <a href="{{ route('ordens.create') }}"
                       class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold text-white
                              bg-verdes-verde_claro hover:bg-verdes-verde_folha transition">
                        Abrir nova OS
                    </a>
                </div>

                <div class="px-6 pb-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                        <thead class="sticky top-0 z-10 bg-white dark:bg-gray-900">
                            <tr>
                                <th class="pl-3 pr-3 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Codigo
                                </th>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Criada em
                                </th>
                                <th class="px-3 py-3 text-right text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Acoes
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            @forelse($ordens ?? [] as $os)
                                @php
                                    $status = strtolower($os->status ?? '');
                                    $statusLabel = match ($status) {
                                        'aberta'       => 'ABERTA',
                                        'em_execucao'  => 'EM EXECUCAO',
                                        'pausada'      => 'PAUSADA',
                                        'concluida'    => 'CONCLUIDA',
                                        'cancelada'    => 'CANCELADA',
                                        default        => strtoupper(str_replace('_', ' ', $os->status ?? '-')),
                                    };
                                    $statusIcon = match ($status) {
                                        'aberta'      => 'imagem/engrenagem_alerta.png',
                                        'em_execucao' => 'imagem/engrenagem_play.png',
                                        'pausada'     => 'imagem/engrenagem_alerta.png',
                                        'concluida'   => 'imagem/engrenagem.png',
                                        'cancelada'   => 'imagem/engrenagem_alerta.png',
                                        default       => 'imagem/engrenagem_alerta.png',
                                    };

                                    $statusIconDark = match ($status) {
                                        'aberta'      => 'imagem/engrenagem_alerta_white.png',
                                        'em_execucao' => 'imagem/engrenagem_play_white.png',
                                        'pausada'     => 'imagem/engrenagem_alerta_white.png',
                                        'concluida'   => 'imagem/engrenagem_white.png',
                                        'cancelada'   => 'imagem/engrenagem_alerta_white.png',
                                        default       => 'imagem/engrenagem_alerta_white.png',
                                    };

                                    $prioridadeRaw = $os->prioridade ?? null;
                                    $prioridade = $prioridadeRaw ? strtolower(trim($prioridadeRaw)) : null;

                                    $priorityBarClass = match (true) {
                                        $prioridade === 'alto'
                                            || $prioridade === 'muito_alto' => 'bg-red-500',
                                        $prioridade === 'medio'            => 'bg-yellow-400',
                                        $prioridade === 'baixo'            => 'bg-verdes-verde_claro',
                                        default                            => 'bg-verdes-verde_claro/40',
                                    };

                                    $setorNome = $os->setor->nome
                                        ?? $os->equipamento->setor->nome
                                        ?? '-';

                                    $temAtribuicoes = ($os->tecnicos_count ?? 0) > 0 || ($os->gestores_count ?? 0) > 0;
                                    $podeEditar = $status === 'aberta' && ! $temAtribuicoes;
                                @endphp

                                <tr class="hover:bg-gray-50/70 dark:hover:bg-white/5 transition">
                                    <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-11 w-11 items-center justify-center rounded-md {{ $priorityBarClass }}"
                                                  title="Status {{ $statusLabel }}">
                                                <img src="{{ asset($statusIcon) }}"
                                                     alt="Status {{ $statusLabel }}"
                                                     class="h-8 w-8 object-contain opacity-90 dark:hidden">
                                                <img src="{{ asset($statusIconDark) }}"
                                                     alt="Status {{ $statusLabel }}"
                                                     class="hidden h-8 w-8 object-contain opacity-90 dark:block">
                                            </span>
                                            <div class="min-w-0 leading-tight">
                                                <div class="font-semibold">#{{ $os->codigo ?? $os->id }}</div>
                                                <div class="mt-0.5 max-w-[260px] text-xs font-normal text-gray-600 dark:text-gray-300 truncate"
                                                     title="{{ $setorNome }} - {{ $os->equipamento->nome ?? '-' }}">
                                                    {{ $setorNome }} - {{ $os->equipamento->nome ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->created_at?->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-3 py-3 text-sm text-right">
                                        <a href="{{ route('ordens.show', $os) }}"
                                           class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold
                                                  bg-verdes-verde_claro/10 text-verdes-verde_claro hover:bg-verdes-verde_claro/15
                                                  dark:bg-verdes-verde_claro/15 dark:text-verdes-verde_claro dark:hover:bg-verdes-verde_claro/25 transition">
                                            Visualizar O.S
                                            <span class="text-verdes-verde_claro">-></span>
                                        </a>
                                        @if($podeEditar)
                                            <a href="{{ route('ordens.edit', $os) }}"
                                               class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold
                                                      bg-verdes-verde_claro/10 text-verdes-verde_claro hover:bg-verdes-verde_claro/15
                                                      dark:bg-verdes-verde_claro/15 dark:text-verdes-verde_claro dark:hover:bg-verdes-verde_claro/25 transition ms-2">
                                                Editar
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Voce ainda nao abriu nenhuma OS.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $ordens->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== Estilos locais ===== --}}
    <style>
        .animate-in { animation: fadeUp .55s cubic-bezier(.2,.9,.2,1) both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .ui-card{
            background: rgba(255,255,255,.80);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,.06);
            border: 1px solid rgba(0,0,0,.05);
        }
        .dark .ui-card{
            background: rgba(31,41,55,.70);
            border-color: rgba(255,255,255,.08);
        }

        /* KPI Cards */
        .kpi-card{
            background: rgba(255,255,255,.80);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            border: 1px solid rgba(0,0,0,.05);
            box-shadow: 0 1px 2px rgba(0,0,0,.06);
            padding: 1.25rem;
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .dark .kpi-card{ background: rgba(31,41,55,.70); border-color: rgba(255,255,255,.08); }
        .kpi-card:hover{ transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,.08); }
        .kpi-topline{ display:flex; align-items:center; justify-content:space-between; gap:.5rem; }
        .kpi-label{ font-size:.75rem; font-weight:700; color: rgba(107,114,128,1); }
        .dark .kpi-label{ color: rgba(156,163,175,1); }
        .kpi-value{ margin-top:.6rem; font-size:1.75rem; font-weight:800; color: rgba(17,24,39,1); letter-spacing:-.02em; }
        .dark .kpi-value{ color: rgba(243,244,246,1); }
        .kpi-sub{ margin-top:.25rem; font-size:.75rem; color: rgba(107,114,128,1); }
        .dark .kpi-sub{ color: rgba(156,163,175,1); }
        .kpi-pill{
            font-size:.70rem; font-weight:700; padding:.25rem .5rem; border-radius:.6rem;
            background: rgba(243,244,246,1); color: rgba(55,65,81,1);
        }
        .dark .kpi-pill{ background: rgba(255,255,255,.10); color: rgba(229,231,235,1); }
        .kpi-dot{ width:.6rem; height:.6rem; border-radius:999px; }
        .kpi-bar{ margin-top: .9rem; height: .45rem; border-radius: 999px; overflow:hidden; }
        .kpi-bar-fill{ height: 100%; border-radius: 999px; }
    </style>
</x-app-layout>
