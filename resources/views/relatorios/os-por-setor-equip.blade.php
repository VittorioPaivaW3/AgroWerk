@php
    $printUrl = request()->fullUrlWithQuery(['print' => 1]);
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            OS por setor e equipamento
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
                <div class="px-6 py-5 space-y-3">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatorio</p>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 leading-tight">
                                OS por setor e equipamento
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Ranking por quantidade de OS no período.
                            </p>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 text-right">
                            <p>Período: {{ $periodo['inicio']->format('d/m/Y') }} a {{ $periodo['fim']->format('d/m/Y') }}</p>
                            <p>Total de OS: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $totalGeral }}</span></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-200">
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-800">
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ranking de setores</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Top ocorrência</span>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($rankingSetores as $index => $item)
                                <div class="flex items-center justify-between py-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $item->setor->nome ?? 'Sem setor' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Posição {{ $index + 1 }}º</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $item->total }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="py-3 text-sm text-gray-500 dark:text-gray-400">Nenhuma OS no período.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-800">
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ranking de equipamentos</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Top ocorrência</span>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($rankingEquipamentos as $index => $item)
                                <div class="flex items-center justify-between py-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $item->equipamento->nome ?? 'Sem equipamento' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Setor: {{ $item->equipamento->setor->nome ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Posição {{ $index + 1 }}º</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $item->total }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="py-3 text-sm text-gray-500 dark:text-gray-400">Nenhuma OS no período.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
