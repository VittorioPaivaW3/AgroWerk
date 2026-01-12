@php
    use Illuminate\Support\Str;

    $statusOrder = $statusOrder ?? ['aberta', 'em_execucao', 'concluida', 'cancelada'];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Relatório: OS por status
        </h2>
    </x-slot>

    <style>
        @media print {
            nav,
            .no-print {
                display: none !important;
            }
            body {
                background: #fff !important;
            }
            main {
                padding: 0 !important;
            }
            .shadow,
            .shadow-sm {
                box-shadow: none !important;
            }
        }
    </style>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between no-print">
                <a href="{{ route('relatorios.index') }}"
                   class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    ← Voltar
                </a>
                <div class="flex items-center gap-2">
                    @php
                        $printUrl = request()->fullUrlWithQuery(['print' => 1]);
                    @endphp
                    <a href="{{ $printUrl }}"
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 rounded-md bg-verdes-verde_claro text-white text-sm font-semibold hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                        Imprimir
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro">
                <div class="px-6 py-5 space-y-3">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatório</p>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 leading-tight">
                                OS por status e período
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Página pronta para impressão com filtros de período, setor e equipamento.
                            </p>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 text-right">
                            <p>Gerado em {{ now()->format('d/m/Y H:i') }}</p>
                            <p>Total de OS: <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $totalGeral }}</span></p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 text-sm text-gray-700 dark:text-gray-200">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                            <span class="font-semibold">Período:</span>
                            {{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                            <span class="font-semibold">Setor:</span>
                            {{ $setorSelecionado->nome ?? 'Todos' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                            <span class="font-semibold">Equipamento:</span>
                            {{ $equipamentoSelecionado->nome ?? 'Todos' }}
                            @if($equipamentoSelecionado?->setor?->nome)
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $equipamentoSelecionado->setor->nome }})</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($statusOrder as $statusKey)
                    @php
                        $count = (int)($statusCounts[$statusKey] ?? 0);
                        $label = $statusLabels[$statusKey] ?? Str::title(str_replace('_', ' ', $statusKey));
                        $percent = $totalGeral > 0 ? round(($count / $totalGeral) * 100) : 0;
                        $chipClass = 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-900/40 dark:text-gray-200 dark:border-gray-700';
                    @endphp

                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro border border-gray-100 dark:border-gray-800">
                        <div class="px-5 py-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $label }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold rounded-full border {{ $chipClass }}">
                                    {{ $percent }}%
                                </span>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($count, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">do total no período</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ordens no período</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Intervalo: {{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Código</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setor</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Equipamento</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Criada em</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($ordens as $ordem)
                                    @php
                                        $statusKey = $ordem->status ?? '';
                                        $statusLabel = $statusLabels[$statusKey] ?? Str::title(str_replace('_', ' ', $statusKey));
                                        $setorNome = $ordem->setor->nome
                                            ?? $ordem->equipamento->setor->nome
                                            ?? '-';
                                        $descricaoCurta = $ordem->descricao
                                            ? Str::limit(strip_tags($ordem->descricao), 110)
                                            : '-';
                                        $statusChip = 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-900/40 dark:text-gray-200 dark:border-gray-700';
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">#{{ $ordem->codigo ?? $ordem->id }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $statusChip }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $setorNome }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $ordem->equipamento->nome ?? '-' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $ordem->created_at?->format('d/m/Y H:i') }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200">{{ $descricaoCurta }}</td>
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
