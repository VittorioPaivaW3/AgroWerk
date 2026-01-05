<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Cabeçalho / intro --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Central de relatórios
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Aqui você encontra relatórios para acompanhar desempenho da manutenção,
                        custos e indicadores dos projetos. Aos poucos vamos liberando mais opções.
                    </p>
                </div>
            </div>

            {{-- Grid de relatórios --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                {{-- 1. OS por status / período --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                OS por status e período
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Disponível em breve
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Quantidade de ordens abertas, em execução, concluídas e canceladas
                            em um intervalo de datas, com filtros por setor e equipamento.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                       cursor-not-allowed opacity-60">
                            Ver relatório
                        </button>
                    </div>
                </div>

                {{-- 2. Tempo médio de atendimento --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Tempo médio de atendimento
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Planejado
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Mede o tempo entre abertura, início de execução e conclusão das OS.
                            Pode ser filtrado por técnico, setor e tipo de manutenção.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                       cursor-not-allowed opacity-60">
                            Ver relatório
                        </button>
                    </div>
                </div>

                {{-- 3. Custo das OS por período --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Custo das OS por período
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Planejado
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Soma de custos de mão de obra e custos totais das ordens concluídas
                            em um período, com visão por setor e por equipamento.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                       cursor-not-allowed opacity-60">
                            Ver relatório
                        </button>
                    </div>
                </div>

                {{-- 4. OS por setor / equipamento --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                OS por setor e equipamento
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Em breve
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Ranking de setores e equipamentos com maior número de ocorrências,
                            ajudando a identificar gargalos e ativos críticos.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                       cursor-not-allowed opacity-60">
                            Ver relatório
                        </button>
                    </div>
                </div>

                {{-- 5. Produtividade por técnico --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Produtividade por técnico
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Ideia
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Quantidade de OS executadas, tempo médio por OS e custo estimado
                            de mão de obra por técnico.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                       cursor-not-allowed opacity-60">
                            Ver relatório
                        </button>
                    </div>
                </div>

                {{-- 6. Projetos / investimentos --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Projetos e investimentos
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Para projetos
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Visão dos projetos cadastrados, comparando orçamento previsto x
                            orçamento realizado, por setor e por período.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                       cursor-not-allowed opacity-60">
                            Ver relatório
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
