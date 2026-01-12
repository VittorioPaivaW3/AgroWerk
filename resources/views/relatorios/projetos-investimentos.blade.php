@php
    $printUrl = request()->fullUrlWithQuery(['print' => 1]);
    $formatMoney = function (?float $value) {
        if ($value === null) {
            return null;
        }
        return 'R$ ' . number_format($value, 2, ',', '.');
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Projetos e investimentos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('relatorios.index') }}"
                   class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    ← Voltar
                </a>
                <a href="{{ $printUrl }}"
                   target="_blank"
                   class="inline-flex items-center px-3 py-2 rounded-md bg-verdes-verde_claro text-white text-sm font-semibold hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                    Imprimir
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-800">
                <div class="px-6 py-5 space-y-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatorio</p>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 leading-tight">
                                Projetos e investimentos
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Orçamento previsto x realizado no período filtrado.
                            </p>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 text-right">
                            <p>Período: {{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}</p>
                            <p>Total de projetos: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $totalProjetos }}</span></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm text-gray-700 dark:text-gray-200">
                        <div class="rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Setor</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                                {{ $setorSelecionado->nome ?? 'Todos os setores' }}
                            </p>
                        </div>
                        <div class="rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Status</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                                {{ $statusLabels[$statusSelecionado] ?? ($statusSelecionado ? $statusSelecionado : 'Todos') }}
                            </p>
                        </div>
                        <div class="rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Orçamento previsto (total)</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                                {{ $formatMoney($totalPrevisto) ?? 'Sem dados' }}
                            </p>
                        </div>
                        <div class="rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Orçamento realizado (total)</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                                {{ $formatMoney($totalReal) ?? 'Sem dados' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-800 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Total de projetos</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $totalProjetos }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Projetos no período filtrado.
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-800 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Variação total</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $formatMoney($totalVariacao) ?? 'Sem dados' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Real - Previsto (soma de todos).
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-800 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Orçamento médio (real)</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $totalProjetos ? $formatMoney($totalReal / max($totalProjetos,1)) : 'Sem dados' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Média entre projetos filtrados.
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Projetos no período</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Intervalo: {{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Título</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setor</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prazo</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Previsto</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Real</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Variação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($projetos as $proj)
                                    @php
                                        $variacaoFmt = $proj->variacao_calc !== null ? $formatMoney((float) $proj->variacao_calc) : '-';
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $proj->titulo }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $proj->setor->nome ?? '-' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $statusLabels[$proj->status] ?? $proj->status }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $proj->prazo?->format('d/m/Y') ?? '-' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $proj->orcamento_previsto !== null ? $formatMoney((float) $proj->orcamento_previsto) : '-' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $proj->orcamento_real !== null ? $formatMoney((float) $proj->orcamento_real) : '-' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $variacaoFmt }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Nenhum projeto encontrado para o período selecionado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
