<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard de Manutenção') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Visão geral e desempenho do período selecionado
                </p>
            </div>

            {{-- Dropdown Filtros (no header) --}}
            <div class="relative" x-data="{ open:false }" @keydown.escape.window="open=false">
                <button type="button"
                        @click="open = !open"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold
                               bg-white/80 hover:bg-white text-gray-700
                               dark:bg-gray-800/70 dark:hover:bg-gray-800 dark:text-gray-200
                               ring-1 ring-black/5 dark:ring-white/10
                               shadow-sm transition">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 3H2l8 9v7l4 2v-9l8-9z"/>
                        </svg>
                    </span>
                    Filtros
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>

                {{-- Backdrop para fechar clicando fora --}}
                <div x-show="open"
                     x-transition.opacity
                     class="fixed inset-0 z-40"
                     @click="open=false"
                     style="display:none"></div>

                {{-- Painel dropdown --}}
                <div x-show="open"
                     x-transition
                     @click.outside="open=false"
                     style="display:none"
                     class="absolute right-0 mt-2 w-[min(92vw,52rem)] z-50">
                    <div class="ui-card p-5">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Filtros</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Refine por período, setor, técnico e status.
                                </p>
                            </div>
                            <button type="button"
                                    @click="open=false"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-xl
                                           bg-gray-100 hover:bg-gray-200 text-gray-700
                                           dark:bg-white/10 dark:hover:bg-white/15 dark:text-gray-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6L6 18M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <form method="GET" action="{{ route('dashboard') }}"
                              class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                            <div>
                                <x-input-label for="inicio" value="Início" />
                                <x-text-input id="inicio" name="inicio" type="date" class="mt-1 block w-full"
                                              :value="request('inicio')" />
                            </div>

                            <div>
                                <x-input-label for="fim" value="Fim" />
                                <x-text-input id="fim" name="fim" type="date" class="mt-1 block w-full"
                                              :value="request('fim')" />
                            </div>

                            <div>
                                <x-input-label for="setor_id" value="Setor" />
                                <select id="setor_id" name="setor_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                                               focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500">
                                    <option value="">Todos</option>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}" @selected(request('setor_id') == $setor->id)>{{ $setor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="tecnico_id" value="Técnico" />
                                <select id="tecnico_id" name="tecnico_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                                               focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500">
                                    <option value="">Todos</option>
                                    @foreach($tecnicos as $tec)
                                        <option value="{{ $tec->id }}" @selected(request('tecnico_id') == $tec->id)>{{ $tec->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="status" value="Status" />
                                <select id="status" name="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm
                                               focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500">
                                    <option value="">Todos</option>
                                    <option value="aberta" @selected(request('status') === 'aberta')>Aberta</option>
                                    <option value="em_execucao" @selected(request('status') === 'em_execucao')>Em execução</option>
                                    <option value="concluida" @selected(request('status') === 'concluida')>Concluída</option>
                                    <option value="cancelada" @selected(request('status') === 'cancelada')>Cancelada</option>
                                </select>
                            </div>

                            <div class="md:col-span-5 flex flex-wrap gap-2 pt-1">
                                <button type="submit"
                                        class="group inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-xs uppercase tracking-widest
                                          bg-gray-100 hover:bg-gray-200 text-gray-700
                                          dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200
                                          shadow-sm active:scale-[0.98] transition">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 3H2l8 9v7l4 2v-9l8-9z"/>
                                        </svg>
                                    </span>
                                    Filtrar
                                </button>

                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-xs uppercase tracking-widest
                                          bg-gray-100 hover:bg-gray-200 text-gray-700
                                          dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200
                                          shadow-sm active:scale-[0.98] transition">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18M8 6V4h8v2M6 6l1 16h10l1-16"/>
                                        </svg>
                                    </span>
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- /Dropdown --}}
        </div>
    </x-slot>

    @php
        $statusCounts  = $statusCounts  ?? [];
        $mensalSeries  = $mensalSeries  ?? collect();
        $setorCounts   = $setorCounts   ?? collect();
        $tecnicoCounts = $tecnicoCounts ?? collect();
        $tipoCounts    = $tipoCounts    ?? collect();

        // ✅ Normaliza statusCounts: pode vir array OU Collection
        $statusCountsArr = $statusCounts instanceof \Illuminate\Support\Collection
            ? $statusCounts->toArray()
            : (is_array($statusCounts) ? $statusCounts : []);

        // Paleta unificada de status
        $statusPalette = [
            'aberta'      => ['hex' => '#fbbf24', 'tw' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/35 dark:text-yellow-200'],
            'em_execucao' => ['hex' => '#60a5fa', 'tw' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/35 dark:text-blue-200'],
            'concluida'   => ['hex' => '#34d399', 'tw' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/35 dark:text-emerald-200'],
            'cancelada'   => ['hex' => '#f87171', 'tw' => 'bg-red-100 text-red-800 dark:bg-red-900/35 dark:text-red-200'],
        ];

        $totalOS = array_sum(array_map('intval', $statusCountsArr));
    @endphp

    <div class="relative py-8">

        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ===== KPI Topo ===== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Total --}}
                <div class="kpi-card animate-in" style="animation-delay: 60ms">
                    <div class="kpi-topline">
                        <p class="kpi-label">Total de OS</p>
                        <span class="kpi-pill">Período</span>
                    </div>
                    <p class="kpi-value">{{ $totalOS ?? 0 }}</p>
                </div>

                {{-- Abertas --}}
                <div class="kpi-card animate-in" style="animation-delay: 120ms">
                    <div class="kpi-topline">
                        <p class="kpi-label">OS Abertas</p>
                        <span class="kpi-dot" style="background: {{ $statusPalette['aberta']['hex'] }}"></span>
                    </div>
                    <p class="kpi-value">{{ $osAbertas ?? 0 }}</p>
                    <p class="kpi-sub">Aguardando atendimento</p>
                </div>

                {{-- Em execução --}}
                <div class="kpi-card animate-in" style="animation-delay: 180ms">
                    <div class="kpi-topline">
                        <p class="kpi-label">Em Execução</p>
                        <span class="kpi-dot" style="background: {{ $statusPalette['em_execucao']['hex'] }}"></span>
                    </div>
                    <p class="kpi-value">{{ $osEmExecucao ?? 0 }}</p>
                    <p class="kpi-sub">Em andamento</p>
                </div>

                {{-- Concluídas hoje --}}
                <div class="kpi-card animate-in" style="animation-delay: 240ms">
                    <div class="kpi-topline">
                        <p class="kpi-label">Concluídas Hoje</p>
                        <span class="kpi-dot" style="background: {{ $statusPalette['concluida']['hex'] }}"></span>
                    </div>
                    <p class="kpi-value">{{ $osConcluidasHoje ?? 0 }}</p>
                    <p class="kpi-sub">Finalizadas hoje</p>
                </div>
            </div>

            {{-- ===== Gráficos ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- Principal grande: Mensal --}}
                <div class="chart-card chart-card--xl lg:col-span-8 animate-in" style="animation-delay: 140ms">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">OS criadas (últimos meses)</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Tendência do volume</p>
                            </div>
                            <button type="button"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg
                                           bg-gray-100 hover:bg-gray-200 text-gray-700
                                           dark:bg-white/10 dark:hover:bg-white/15 dark:text-gray-200 transition">
                                Actions
                            </button>
                        </div>
                        <div class="chart-wrap chart-wrap--xl">
                            <canvas id="chartMensal"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Lateral: Status doughnut + % --}}
                <div class="chart-card chart-card--xl lg:col-span-4 animate-in" style="animation-delay: 200ms">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Status</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Distribuição</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div class="chart-wrap chart-wrap--donut">
                                <canvas id="chartStatus"></canvas>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusPalette['aberta']['tw'] }}">
                                    <span class="h-2 w-2 rounded-full" style="background: {{ $statusPalette['aberta']['hex'] }}"></span>
                                    Aberta
                                </span>
                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusPalette['em_execucao']['tw'] }}">
                                    <span class="h-2 w-2 rounded-full" style="background: {{ $statusPalette['em_execucao']['hex'] }}"></span>
                                    Em execução
                                </span>
                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusPalette['concluida']['tw'] }}">
                                    <span class="h-2 w-2 rounded-full" style="background: {{ $statusPalette['concluida']['hex'] }}"></span>
                                    Concluída
                                </span>

                                @if(isset($statusCountsArr['cancelada']))
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusPalette['cancelada']['tw'] }}">
                                        <span class="h-2 w-2 rounded-full" style="background: {{ $statusPalette['cancelada']['hex'] }}"></span>
                                        Cancelada
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Setor --}}
                <div class="chart-card chart-card--lg lg:col-span-4 animate-in order-1 lg:order-1" style="animation-delay: 240ms">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">OS por setor</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Concentração da demanda</p>
                            </div>
                        </div>
                        <div class="chart-wrap chart-wrap--lg">
                            <canvas id="chartSetor"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Técnico --}}
                <div class="chart-card chart-card--lg lg:col-span-4 animate-in order-3 lg:order-3" style="animation-delay: 280ms">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">OS por técnico</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Distribuição por responsável</p>
                            </div>
                        </div>
                        <div class="chart-wrap chart-wrap--lg">
                            <canvas id="chartTecnico"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Tipo de manutençõo --}}
                <div class="chart-card chart-card--lg lg:col-span-4 animate-in order-2 lg:order-2" style="animation-delay: 260ms">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">OS por tipo de manutenção</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Corretiva x preventiva</p>
                            </div>
                        </div>
                        <div class="chart-wrap chart-wrap--lg">
                            <canvas id="chartTipo"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Tabela últimas OS ===== --}}
            <div class="ui-card animate-in" style="animation-delay: 320ms">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700/60">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Últimas Ordens de Serviço
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Acompanhe rapidamente as últimas movimentações.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Código</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Solicitante</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prioridade</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Criada em</th>
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($ordens ?? [] as $os)
                            @php
                                $statusRaw = $os->status ?? null;
                                $status = $statusRaw ? strtolower(trim($statusRaw)) : null;

                                $statusLabel = match ($status) {
                                    'aberta'       => 'Aberta',
                                    'em_execucao'  => 'Em execução',
                                    'concluida'    => 'Concluída',
                                    'cancelada'    => 'Cancelada',
                                    default        => $statusRaw ? ucfirst(str_replace('_', ' ', $statusRaw)) : '—',
                                };

                                $statusClasses = $statusPalette[$status]['tw'] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';

                                $prioridadeRaw = $os->prioridade ?? null;
                                $prioridade = $prioridadeRaw ? strtolower(trim($prioridadeRaw)) : null;

                                $prioridadeLabel = match ($prioridade) {
                                    'baixo'              => 'Baixo',
                                    'medio', 'médio'     => 'Médio',
                                    'alto'               => 'Alto',
                                    'muito_alto'         => 'Muito alto',
                                    default              => $prioridadeRaw ? ucfirst($prioridadeRaw) : '—',
                                };

                                $prioridadeClasses = match ($prioridade) {
                                    'baixo'              => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/35 dark:text-emerald-200',
                                    'medio', 'médio'     => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/35 dark:text-yellow-200',
                                    'alto'               => 'bg-orange-100 text-orange-800 dark:bg-orange-900/35 dark:text-orange-200',
                                    'muito_alto'         => 'bg-red-100 text-red-800 dark:bg-red-900/35 dark:text-red-200',
                                    default              => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/25 transition">
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                    <span class="font-semibold">#{{ $os->codigo ?? $os->id }}</span>
                                </td>

                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $os->solicitante->name ?? '-' }}
                                </td>

                                <td class="px-3 py-3 text-sm">
                                    <span class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="px-3 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $prioridadeClasses }}">
                                        {{ $prioridadeLabel }}
                                    </span>
                                </td>

                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                    {{ $os->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                <td class="px-3 py-3 text-sm text-right whitespace-nowrap">
                                    <a href="{{ route('ordens.show', $os) }}"
                                       class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300
                                              text-xs font-semibold transition">
                                        Ver detalhes
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M5 12h14M13 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Nenhuma ordem de serviço encontrada.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
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

        /* Chart Cards */
        .chart-card{
            background: rgba(255,255,255,.80);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,.06);
            border: 1px solid rgba(0,0,0,.05);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .dark .chart-card{
            background: rgba(31,41,55,.70);
            border-color: rgba(255,255,255,.08);
        }
        .chart-card:hover{
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }

        /* Alturas maiores */
        .chart-wrap{ height: 340px; }
        .chart-wrap--lg{ height: 360px; }
        .chart-wrap--xl{ height: 420px; }
        .chart-wrap--donut{ height: 320px; display:flex; align-items:center; justify-content:center; }

        @media (max-width: 1024px){
            .chart-wrap--xl{ height: 360px; }
        }
        @media (max-width: 640px){
            .chart-wrap, .chart-wrap--lg, .chart-wrap--xl, .chart-wrap--donut{ height: 300px; }
        }
    </style>

    {{-- ===== Chart.js ===== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // =========================
    // THEME HELPERS
    // =========================
    const getTheme = () => {
      const isDark = document.documentElement.classList.contains('dark');
      return {
        isDark,
        gridColor:  isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)',
        tickColor:  isDark ? 'rgba(255,255,255,0.70)' : 'rgba(0,0,0,0.60)',
        labelColor: isDark ? 'rgba(255,255,255,0.90)' : 'rgba(0,0,0,0.85)',
        tooltipBg:  isDark ? 'rgba(17,24,39,0.92)' : 'rgba(255,255,255,0.95)',
        tooltipBorder: isDark ? 'rgba(255,255,255,0.10)' : 'rgba(0,0,0,0.08)',
        donutBorder: isDark ? 'rgba(17,24,39,0.6)' : 'rgba(255,255,255,0.9)',
      };
    };

    const STATUS = {
      aberta:      { label: 'Aberta',      color: '#fbbf24' },
      em_execucao: { label: 'Em execução', color: '#60a5fa' },
      concluida:   { label: 'Concluída',   color: '#34d399' },
      cancelada:   { label: 'Cancelada',   color: '#f87171' },
    };

    Chart.defaults.font.family = 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial';
    Chart.defaults.animation.duration = 900;
    Chart.defaults.animation.easing = 'easeOutQuart';
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;

    const baseScalesY = (t) => ({
      beginAtZero: true,
      grid: { color: t.gridColor, drawBorder: false },
      ticks: { precision: 0, color: t.tickColor }
    });

    const baseScalesX = (t) => ({
      grid: { color: t.gridColor, drawBorder: false },
      ticks: { color: t.tickColor }
    });

    const basePlugins = (t) => ({
      legend: { display: false },
      tooltip: {
        backgroundColor: t.tooltipBg,
        titleColor: t.labelColor,
        bodyColor: t.labelColor,
        borderColor: t.tooltipBorder,
        borderWidth: 1,
        padding: 12,
        cornerRadius: 10,
        displayColors: true,
      }
    });

    const staggerDelay = (ctx, step=70) => (ctx.type === 'data' ? ctx.dataIndex * step : 0);

    // =========================
    // DATA (mesmos dados que você já tinha)
    // =========================
    const statusDataRaw = @json($statusCountsArr ?? []);
    const statusOrder = ['aberta','em_execucao','concluida','cancelada'].filter(k => k in statusDataRaw);
    const statusLabels = statusOrder.map(k => STATUS[k]?.label ?? k);
    const statusValues = statusOrder.map(k => Number(statusDataRaw[k] ?? 0));
    const statusColors = statusOrder.map(k => STATUS[k]?.color ?? '#9ca3af');

    const total = statusValues.reduce((a,b)=>a+b,0);
    const concluidaIdx = statusOrder.indexOf('concluida');
    const concluidaVal = concluidaIdx >= 0 ? statusValues[concluidaIdx] : 0;
    const pctConcluida = total > 0 ? Math.round((concluidaVal / total) * 100) : 0;

    const mensalSeries = @json(($mensalSeries ?? collect())->values());
    const mensalLabels = mensalSeries.map(i => i.label);
    const mensalValues = mensalSeries.map(i => Number(i.total ?? 0));

    const setorCounts = @json(($setorCounts ?? collect())->values());
    const setorLabels = setorCounts.map(i => i.setor);
    const setorValues = setorCounts.map(i => Number(i.total));

    const tecnicoCounts = @json(($tecnicoCounts ?? collect())->values());
    const tecnicoLabels = tecnicoCounts.map(i => i.tecnico);
    const tecnicoValues = tecnicoCounts.map(i => Number(i.total));

    const tipoCounts = @json(($tipoCounts ?? collect())->values());
    const tipoLabels = tipoCounts.map(i => {
      const raw = (i.tipo ?? '').toString().toLowerCase();
      if (raw === 'corretiva') return 'Corretiva';
      if (raw === 'preventiva') return 'Preventiva';
      return raw ? raw.charAt(0).toUpperCase() + raw.slice(1) : 'Outro';
    });
    const tipoValues = tipoCounts.map(i => Number(i.total));

    // =========================
    // Plugin: texto no centro do donut (agora reativo ao dark)
    // =========================
    const CenterText = {
      id: 'centerText',
      afterDraw(chart, args, pluginOptions) {
        const { ctx, chartArea } = chart;
        if (!chartArea) return;

        const t = getTheme();

        const { left, right, top, bottom } = chartArea;
        const x = (left + right) / 2;
        const y = (top + bottom) / 2;

        const title = pluginOptions?.title ?? '';
        const value = pluginOptions?.value ?? '';

        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        ctx.fillStyle = t.isDark ? 'rgba(229,231,235,0.9)' : 'rgba(17,24,39,0.9)';
        ctx.font = '700 26px ui-sans-serif, system-ui';
        ctx.fillText(value, x, y - 6);

        ctx.fillStyle = t.isDark ? 'rgba(156,163,175,0.9)' : 'rgba(107,114,128,0.9)';
        ctx.font = '600 12px ui-sans-serif, system-ui';
        ctx.fillText(title, x, y + 16);

        ctx.restore();
      }
    };
    Chart.register(CenterText);

    // =========================
    // Cria charts + guarda instâncias
    // =========================
    const charts = [];

    const createCharts = () => {
      const t = getTheme();
      Chart.defaults.color = t.tickColor;

      // Mensal
      const ctxMensal = document.getElementById('chartMensal');
      if (ctxMensal) {
        const c = new Chart(ctxMensal, {
          type: 'bar',
          data: {
            labels: mensalLabels,
            datasets: [{
              label: 'OS criadas',
              borderRadius: 10,
              borderSkipped: false,
              data: mensalValues,
              backgroundColor: 'rgba(96, 165, 250, 1)',
              borderWidth: 2,
            }]
          },
          options: {
            plugins: { ...basePlugins(t), legend: { display: false } },
            animation: { delay: (ctx) => staggerDelay(ctx, 35) },
            scales: { y: baseScalesY(t), x: baseScalesX(t) }
          }
        });
        charts.push(c);
      }

      // Status donut
      const ctxStatus = document.getElementById('chartStatus');
      if (ctxStatus) {
        const c = new Chart(ctxStatus, {
          type: 'doughnut',
          data: {
            labels: statusLabels,
            datasets: [{
              data: statusValues,
              backgroundColor: statusColors,
              borderColor: t.donutBorder,
              borderWidth: 2,
              hoverOffset: 6,
            }]
          },
          options: {
            cutout: '72%',
            plugins: {
              ...basePlugins(t),
              centerText: { title: 'Concluídas', value: `${pctConcluida}%` },
            },
            animation: { duration: 1100, easing: 'easeOutQuart' },
          }
        });
        charts.push(c);
      }

      // Setor
      const ctxSetor = document.getElementById('chartSetor');
      if (ctxSetor) {
        const c = new Chart(ctxSetor, {
          type: 'bar',
          data: {
            labels: setorLabels,
            datasets: [{
              label: 'OS',
              data: setorValues,
              backgroundColor: STATUS.concluida.color,
              borderRadius: 10,
              borderSkipped: false,
            }]
          },
          options: {
            indexAxis: 'y',
            plugins: basePlugins(t),
            animation: { delay: (ctx) => staggerDelay(ctx, 25) },
            scales: {
              x: { ...baseScalesY(t), grid: { color: t.gridColor, drawBorder: false } },
              y: { ...baseScalesX(t), ticks: { ...baseScalesX(t).ticks, color: t.tickColor } }
            }
          }
        });
        charts.push(c);
      }

      // Técnico
      const ctxTecnico = document.getElementById('chartTecnico');
      if (ctxTecnico) {
        const c = new Chart(ctxTecnico, {
          type: 'bar',
          data: {
            labels: tecnicoLabels,
            datasets: [{
              label: 'OS',
              data: tecnicoValues,
              backgroundColor: STATUS.aberta.color,
              borderRadius: 10,
              borderSkipped: false,
            }]
          },
          options: {
            indexAxis: 'y',
            plugins: basePlugins(t),
            animation: { delay: (ctx) => staggerDelay(ctx, 25) },
            scales: {
              x: { ...baseScalesY(t), grid: { color: t.gridColor, drawBorder: false } },
              y: { ...baseScalesX(t), ticks: { ...baseScalesX(t).ticks, color: t.tickColor } }
            }
          }
        });
        charts.push(c);
      }

      // Tipo de manutenÇõo
      const ctxTipo = document.getElementById('chartTipo');
      if (ctxTipo) {
        const c = new Chart(ctxTipo, {
          type: 'bar',
          data: {
            labels: tipoLabels,
            datasets: [{
              label: 'OS',
              data: tipoValues,
              backgroundColor: STATUS.cancelada.color,
              borderRadius: 10,
              borderSkipped: false,
            }]
          },
          options: {
            plugins: basePlugins(t),
            animation: { delay: (ctx) => staggerDelay(ctx, 25) },
            scales: { y: baseScalesY(t), x: baseScalesX(t) }
          }
        });
        charts.push(c);
      }
    };

    // =========================
    // Atualiza tema dos charts ao trocar dark/light
    // =========================
    const applyThemeToCharts = () => {
      const t = getTheme();
      Chart.defaults.color = t.tickColor;

      charts.forEach((chart) => {
        // scales (se existir)
        if (chart.options?.scales) {
          Object.values(chart.options.scales).forEach((s) => {
            if (s?.ticks) s.ticks.color = t.tickColor;
            if (s?.grid)  s.grid.color  = t.gridColor;
          });
        }

        // tooltip
        if (chart.options?.plugins?.tooltip) {
          chart.options.plugins.tooltip.backgroundColor = t.tooltipBg;
          chart.options.plugins.tooltip.titleColor = t.labelColor;
          chart.options.plugins.tooltip.bodyColor = t.labelColor;
          chart.options.plugins.tooltip.borderColor = t.tooltipBorder;
        }

        // doughnut border
        if (chart.config?.type === 'doughnut' && chart.data?.datasets?.[0]) {
          chart.data.datasets[0].borderColor = t.donutBorder;
        }

        chart.update();
      });
    };

    // cria inicialmente
    createCharts();

    // observa troca de classe dark/light no <html>
    const observer = new MutationObserver(() => applyThemeToCharts());
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
  });
</script>

</x-app-layout>
