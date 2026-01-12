<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-8" x-data="relatoriosPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 dark:border-red-800/60 dark:bg-red-900/40 px-4 py-3 text-sm text-red-800 dark:text-red-100">
                    {{ session('error') }}
                </div>
            @endif

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
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-white/10 border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                OS por status e período
                            </h4>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Quantidade de ordens abertas, em execução, concluídas e canceladas
                            em um intervalo de datas, com filtros por setor e equipamento.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro"
                                @click="openOsStatusModal()">
                            Escolher período
                        </button>
                    </div>
                </div>

                {{-- 2. Tempo médio de atendimento --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-white/10 border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Tempo médio de atendimento
                            </h4>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Mede o tempo entre abertura, início de execução e conclusão das OS.
                            Pode ser filtrado por técnico, setor e tipo de manutenção.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro"
                                @click="openTempoMedioModal()">
                            Escolher período
                        </button>
                    </div>
                </div>

                {{-- 3. Custo das OS por período --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-white/10 border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Custo das OS por período
                            </h4>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Soma de custos de mão de obra e custos totais das ordens concluídas
                            em um período, com visão por setor e por equipamento.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro"
                                @click="openCustoModal()">
                            Escolher período
                        </button>
                    </div>
                </div>

                {{-- 4. OS por setor / equipamento --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-white/10 border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                OS por setor e equipamento
                            </h4>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Ranking de setores e equipamentos com maior número de ocorrências,
                            ajudando a identificar gargalos e ativos críticos.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro"
                                @click="openSetorEquipModal()">
                            Escolher período
                        </button>
                    </div>
                </div>

                {{-- 5. Produtividade por técnico --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-white/10 border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Produtividade por técnico
                            </h4>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Quantidade de OS executadas, tempo médio por OS e custo estimado
                            de mão de obra por técnico.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro"
                                @click="openProdutividadeModal()">
                            Escolher período
                        </button>
                    </div>
                </div>

                {{-- 6. Projetos / investimentos --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-white/10 border-t-4 border-t-verdes-verde_claro dark:border-t-verdes-verde_claro flex flex-col">
                    <div class="px-5 py-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Projetos e investimentos
                            </h4>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex-1">
                            Visão dos projetos cadastrados, comparando orçamento previsto x
                            orçamento realizado, por setor e por período.
                        </p>
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 rounded-md text-xs font-semibold
                                       bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro"
                                @click="openProjetosModal()">
                            Escolher período
                        </button>
                    </div>
                </div>

            </div>

            <x-modal name="os-status-modal" :show="false" maxWidth="2xl" focusable>
                <div class="px-6 py-5 space-y-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatório</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-snug mt-1">
                                OS por status e período
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Escolha o período e, se quiser, filtre por setor ou equipamento.
                            </p>
                        </div>
                        <button type="button"
                                class="text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-200"
                                @click="closeOsStatusModal()">
                            &times;
                        </button>
                    </div>

                    <form method="GET" action="{{ route('relatorios.os-status') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="inicio">Data inicial</label>
                                <input type="date" id="inicio" name="inicio" x-model="osInicio"
                                       :max="osFim || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="fim">Data final</label>
                                <input type="date" id="fim" name="fim" x-model="osFim"
                                       :min="osInicio || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="setor_id">Setor</label>
                                <select id="setor_id" name="setor_id" x-model="osSetorId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="equipamento_id">Equipamento</label>
                                <select id="equipamento_id" name="equipamento_id" x-model="osEquipamentoId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($equipamentos as $equipamento)
                                        <option value="{{ $equipamento->id }}">
                                            {{ $equipamento->nome }}
                                            @if($equipamento->setor?->nome)
                                                ({{ $equipamento->setor->nome }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                O período é aplicado na data de criação da OS.
                            </p>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                                        @click="resetOsStatusForm()">
                                    Limpar
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                    Gerar página
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

            <x-modal name="tempo-medio-modal" :show="false" maxWidth="2xl" focusable>
                <div class="px-6 py-5 space-y-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatorio</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-snug mt-1">
                                Tempo medio de atendimento
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Selecione periodo e filtros para calcular tempos medios.
                            </p>
                        </div>
                        <button type="button"
                                class="text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-200"
                                @click="closeTempoMedioModal()">
                            &times;
                        </button>
                    </div>

                    <form method="GET" action="{{ route('relatorios.tempo-medio') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tempo_inicio">Data inicial</label>
                                <input type="date" id="tempo_inicio" name="inicio" x-model="tempoInicio"
                                       :max="tempoFim || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tempo_fim">Data final</label>
                                <input type="date" id="tempo_fim" name="fim" x-model="tempoFim"
                                       :min="tempoInicio || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tempo_setor_id">Setor</label>
                                <select id="tempo_setor_id" name="setor_id" x-model="tempoSetorId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tempo_equipamento_id">Equipamento</label>
                                <select id="tempo_equipamento_id" name="equipamento_id" x-model="tempoEquipamentoId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($equipamentos as $equipamento)
                                        <option value="{{ $equipamento->id }}">
                                            {{ $equipamento->nome }}
                                            @if($equipamento->setor?->nome)
                                                ({{ $equipamento->setor->nome }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tempo_tecnico_id">Tecnico</label>
                                <select id="tempo_tecnico_id" name="tecnico_id" x-model="tempoTecnicoId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($tecnicos as $tec)
                                        <option value="{{ $tec->id }}">{{ $tec->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tempo_tipo">Tipo</label>
                                <select id="tempo_tipo" name="tipo" x-model="tempoTipo"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    <option value="corretiva">Corretiva</option>
                                    <option value="preventiva">Preventiva</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                O periodo considera a data de criacao da OS.
                            </p>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                                        @click="resetTempoMedioForm()">
                                    Limpar
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                    Gerar pagina
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

            <x-modal name="custo-os-modal" :show="false" maxWidth="2xl" focusable>
                <div class="px-6 py-5 space-y-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatorio</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-snug mt-1">
                                Custo das OS por periodo
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Filtre por periodo, setor, equipamento, tipo e status.
                            </p>
                        </div>
                        <button type="button"
                                class="text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-200"
                                @click="closeCustoModal()">
                            &times;
                        </button>
                    </div>

                    <form method="GET" action="{{ route('relatorios.custo-os') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="custo_inicio">Data inicial</label>
                                <input type="date" id="custo_inicio" name="inicio" x-model="custoInicio"
                                       :max="custoFim || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="custo_fim">Data final</label>
                                <input type="date" id="custo_fim" name="fim" x-model="custoFim"
                                       :min="custoInicio || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="custo_setor_id">Setor</label>
                                <select id="custo_setor_id" name="setor_id" x-model="custoSetorId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="custo_equipamento_id">Equipamento</label>
                                <select id="custo_equipamento_id" name="equipamento_id" x-model="custoEquipamentoId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($equipamentos as $equipamento)
                                        <option value="{{ $equipamento->id }}">
                                            {{ $equipamento->nome }}
                                            @if($equipamento->setor?->nome)
                                                ({{ $equipamento->setor->nome }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="custo_tipo">Tipo</label>
                                <select id="custo_tipo" name="tipo" x-model="custoTipo"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    <option value="corretiva">Corretiva</option>
                                    <option value="preventiva">Preventiva</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="custo_status">Status</label>
                                <select id="custo_status" name="status" x-model="custoStatus"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    <option value="concluida">Concluida</option>
                                    <option value="em_execucao">Em execucao</option>
                                    <option value="aberta">Aberta</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Considera custo_total informado na OS.
                            </p>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                                        @click="resetCustoForm()">
                                    Limpar
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                    Gerar pagina
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

            <x-modal name="setor-equip-modal" :show="false" maxWidth="2xl" focusable>
                <div class="px-6 py-5 space-y-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatorio</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-snug mt-1">
                                OS por setor e equipamento
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Ranking com base no volume de OS por periodo.
                            </p>
                        </div>
                        <button type="button"
                                class="text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-200"
                                @click="closeSetorEquipModal()">
                            &times;
                        </button>
                    </div>

                    <form method="GET" action="{{ route('relatorios.os-setor-equip') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="setor_equip_inicio">Data inicial</label>
                                <input type="date" id="setor_equip_inicio" name="inicio" x-model="setorEquipInicio"
                                       :max="setorEquipFim || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="setor_equip_fim">Data final</label>
                                <input type="date" id="setor_equip_fim" name="fim" x-model="setorEquipFim"
                                       :min="setorEquipInicio || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="setor_equip_tipo">Tipo</label>
                                <select id="setor_equip_tipo" name="tipo" x-model="setorEquipTipo"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    <option value="corretiva">Corretiva</option>
                                    <option value="preventiva">Preventiva</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="setor_equip_status">Status</label>
                                <select id="setor_equip_status" name="status" x-model="setorEquipStatus"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    <option value="concluida">Concluida</option>
                                    <option value="em_execucao">Em execucao</option>
                                    <option value="aberta">Aberta</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Periodo considera a data de criacao da OS.
                            </p>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                                        @click="resetSetorEquipForm()">
                                    Limpar
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                    Gerar pagina
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

            <x-modal name="produtividade-modal" :show="false" maxWidth="2xl" focusable>
                <div class="px-6 py-5 space-y-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatorio</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-snug mt-1">
                                Produtividade por tecnico
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Ranking de tecnicos por volume de OS, horas e custo estimado.
                            </p>
                        </div>
                        <button type="button"
                                class="text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-200"
                                @click="closeProdutividadeModal()">
                            &times;
                        </button>
                    </div>

                    <form method="GET" action="{{ route('relatorios.produtividade-tecnico') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="prod_inicio">Data inicial</label>
                                <input type="date" id="prod_inicio" name="inicio" x-model="prodInicio"
                                       :max="prodFim || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="prod_fim">Data final</label>
                                <input type="date" id="prod_fim" name="fim" x-model="prodFim"
                                       :min="prodInicio || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="prod_setor_id">Setor</label>
                                <select id="prod_setor_id" name="setor_id" x-model="prodSetorId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="prod_tipo">Tipo</label>
                                <select id="prod_tipo" name="tipo" x-model="prodTipo"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    <option value="corretiva">Corretiva</option>
                                    <option value="preventiva">Preventiva</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="prod_status">Status</label>
                                <select id="prod_status" name="status" x-model="prodStatus"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    <option value="concluida">Concluida</option>
                                    <option value="em_execucao">Em execucao</option>
                                    <option value="aberta">Aberta</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Considera tecnicos vinculados a cada OS.
                            </p>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                                        @click="resetProdutividadeForm()">
                                    Limpar
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                    Gerar pagina
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

            <x-modal name="projetos-modal" :show="false" maxWidth="2xl" focusable>
                <div class="px-6 py-5 space-y-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Relatorio</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-snug mt-1">
                                Projetos e investimentos
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Orçamento previsto x realizado por período e setor.
                            </p>
                        </div>
                        <button type="button"
                                class="text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-200"
                                @click="closeProjetosModal()">
                            &times;
                        </button>
                    </div>

                    <form method="GET" action="{{ route('relatorios.projetos-investimentos') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="proj_inicio">Data inicial</label>
                                <input type="date" id="proj_inicio" name="inicio" x-model="projetosInicio"
                                       :max="projetosFim || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="proj_fim">Data final</label>
                                <input type="date" id="proj_fim" name="fim" x-model="projetosFim"
                                       :min="projetosInicio || null"
                                       required
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="proj_setor_id">Setor</label>
                                <select id="proj_setor_id" name="setor_id" x-model="projetosSetorId"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="proj_status">Status</label>
                                <select id="proj_status" name="status" x-model="projetosStatus"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Todos</option>
                                    <option value="aberto">Aberto</option>
                                    <option value="em_andamento">Em andamento</option>
                                    <option value="concluido">Concluido</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Usa datas de criação dos projetos.
                            </p>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                                        @click="resetProjetosForm()">
                                    Limpar
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold bg-verdes-verde_claro text-white hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                    Gerar pagina
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('relatoriosPage', () => {
                const defaults = {
                    inicio: @js($defaultInicio ?? now()->startOfMonth()->toDateString()),
                    fim: @js($defaultFim ?? now()->toDateString()),
                };

                return {
                    defaultInicio: defaults.inicio,
                    defaultFim: defaults.fim,
                    osInicio: defaults.inicio,
                    osFim: defaults.fim,
                    osSetorId: '',
                    osEquipamentoId: '',
                    tempoInicio: defaults.inicio,
                    tempoFim: defaults.fim,
                    tempoSetorId: '',
                    tempoEquipamentoId: '',
                    tempoTecnicoId: '',
                    tempoTipo: '',
                    custoInicio: defaults.inicio,
                    custoFim: defaults.fim,
                    custoSetorId: '',
                    custoEquipamentoId: '',
                    custoTipo: '',
                    custoStatus: 'concluida',
                    setorEquipInicio: defaults.inicio,
                    setorEquipFim: defaults.fim,
                    setorEquipTipo: '',
                    setorEquipStatus: '',
                    prodInicio: defaults.inicio,
                    prodFim: defaults.fim,
                    prodSetorId: '',
                    prodTipo: '',
                    prodStatus: '',
                    projetosInicio: defaults.inicio,
                    projetosFim: defaults.fim,
                    projetosSetorId: '',
                    projetosStatus: '',
                    openOsStatusModal() {
                        if (!this.osInicio) {
                            this.osInicio = this.defaultInicio;
                        }
                        if (!this.osFim) {
                            this.osFim = this.defaultFim;
                        }
                        this.$dispatch('open-modal', 'os-status-modal');
                    },
                    closeOsStatusModal() {
                        this.$dispatch('close-modal', 'os-status-modal');
                    },
                    resetOsStatusForm() {
                        this.osInicio = this.defaultInicio;
                        this.osFim = this.defaultFim;
                        this.osSetorId = '';
                        this.osEquipamentoId = '';
                    },
                    openTempoMedioModal() {
                        if (!this.tempoInicio) {
                            this.tempoInicio = this.defaultInicio;
                        }
                        if (!this.tempoFim) {
                            this.tempoFim = this.defaultFim;
                        }
                        this.$dispatch('open-modal', 'tempo-medio-modal');
                    },
                    closeTempoMedioModal() {
                        this.$dispatch('close-modal', 'tempo-medio-modal');
                    },
                    resetTempoMedioForm() {
                        this.tempoInicio = this.defaultInicio;
                        this.tempoFim = this.defaultFim;
                        this.tempoSetorId = '';
                        this.tempoEquipamentoId = '';
                        this.tempoTecnicoId = '';
                        this.tempoTipo = '';
                    },
                    openCustoModal() {
                        if (!this.custoInicio) {
                            this.custoInicio = this.defaultInicio;
                        }
                        if (!this.custoFim) {
                            this.custoFim = this.defaultFim;
                        }
                        this.$dispatch('open-modal', 'custo-os-modal');
                    },
                    closeCustoModal() {
                        this.$dispatch('close-modal', 'custo-os-modal');
                    },
                    resetCustoForm() {
                        this.custoInicio = this.defaultInicio;
                        this.custoFim = this.defaultFim;
                        this.custoSetorId = '';
                        this.custoEquipamentoId = '';
                        this.custoTipo = '';
                        this.custoStatus = 'concluida';
                    },
                    setorEquipInicio: defaults.inicio,
                    setorEquipFim: defaults.fim,
                    setorEquipTipo: '',
                    setorEquipStatus: '',
                    openSetorEquipModal() {
                        if (!this.setorEquipInicio) {
                            this.setorEquipInicio = this.defaultInicio;
                        }
                        if (!this.setorEquipFim) {
                            this.setorEquipFim = this.defaultFim;
                        }
                        this.$dispatch('open-modal', 'setor-equip-modal');
                    },
                    closeSetorEquipModal() {
                        this.$dispatch('close-modal', 'setor-equip-modal');
                    },
                    resetSetorEquipForm() {
                        this.setorEquipInicio = this.defaultInicio;
                        this.setorEquipFim = this.defaultFim;
                        this.setorEquipTipo = '';
                        this.setorEquipStatus = '';
                    },
                    prodInicio: defaults.inicio,
                    prodFim: defaults.fim,
                    prodSetorId: '',
                    prodTipo: '',
                    prodStatus: '',
                    openProdutividadeModal() {
                        if (!this.prodInicio) {
                            this.prodInicio = this.defaultInicio;
                        }
                        if (!this.prodFim) {
                            this.prodFim = this.defaultFim;
                        }
                        this.$dispatch('open-modal', 'produtividade-modal');
                    },
                    closeProdutividadeModal() {
                        this.$dispatch('close-modal', 'produtividade-modal');
                    },
                    resetProdutividadeForm() {
                        this.prodInicio = this.defaultInicio;
                        this.prodFim = this.defaultFim;
                        this.prodSetorId = '';
                        this.prodTipo = '';
                        this.prodStatus = '';
                    },
                    projetosInicio: defaults.inicio,
                    projetosFim: defaults.fim,
                    projetosSetorId: '',
                    projetosStatus: '',
                    openProjetosModal() {
                        if (!this.projetosInicio) {
                            this.projetosInicio = this.defaultInicio;
                        }
                        if (!this.projetosFim) {
                            this.projetosFim = this.defaultFim;
                        }
                        this.$dispatch('open-modal', 'projetos-modal');
                    },
                    closeProjetosModal() {
                        this.$dispatch('close-modal', 'projetos-modal');
                    },
                    resetProjetosForm() {
                        this.projetosInicio = this.defaultInicio;
                        this.projetosFim = this.defaultFim;
                        this.projetosSetorId = '';
                        this.projetosStatus = '';
                    },
                };
            });
        });
    </script>
</x-app-layout>
