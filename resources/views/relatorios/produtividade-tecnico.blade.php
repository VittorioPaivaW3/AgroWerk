@php
    $printUrl = request()->fullUrlWithQuery(['print' => 1]);
    $formatHours = function (?float $hours) {
        if ($hours === null) {
            return null;
        }
        $h = floor($hours);
        $m = round(($hours - $h) * 60);
        return sprintf('%02dh %02dmin', $h, $m);
    };
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
            Produtividade por técnico
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

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro border border-gray-100 dark:border-gray-800">
                <div class="px-6 py-5 space-y-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatorio</p>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 leading-tight">
                                Produtividade por técnico
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Volume de OS, horas de execução e custo estimado de mão de obra por técnico.
                            </p>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 text-right">
                            <p>Período: {{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}</p>
                            <p>Total de OS: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $totalGeral }}</span></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-700 dark:text-gray-200">
                        <div class="rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Setor</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                                {{ $setorSelecionado->nome ?? 'Todos os setores' }}
                            </p>
                        </div>
                        <div class="rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Tipo</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                                @if($tipoSelecionado === 'corretiva') Corretiva
                                @elseif($tipoSelecionado === 'preventiva') Preventiva
                                @else Todos @endif
                            </p>
                        </div>
                        <div class="rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Status</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                                {{ $statusLabels[$statusSelecionado] ?? ($statusSelecionado ? $statusSelecionado : 'Todos') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro border border-gray-100 dark:border-gray-800 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Horas de execução (total)</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $formatHours($totalHoras) ?? 'Sem dados' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Somente OS com início e fim de execução.
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro border border-gray-100 dark:border-gray-800 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Custo estimado de mão de obra</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $formatMoney($totalCustoMao) ?? 'Sem dados' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Baseado em valor_hora de cada técnico.
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro border border-gray-100 dark:border-gray-800 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Qtd. de OS</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $totalGeral }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        OS no período filtrado.
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ranking de técnicos</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Intervalo: {{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Posição</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Técnico</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">OS</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">OS concl.</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Horas</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Custo mão de obra</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($ranking as $index => $tec)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $index + 1 }}º</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $tec['nome'] }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $tec['total_os'] }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $tec['os_concluidas'] }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                            {{ $formatHours($tec['horas_execucao']) ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                            {{ $formatMoney($tec['custo_mao_obra']) ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Nenhuma OS encontrada para o período selecionado.
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
