<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard de Manutenção') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Linha de cards de resumo --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {{-- OS Abertas --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            OS Abertas
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $osAbertas ?? 0 }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Ordens de serviço aguardando atendimento.
                        </p>
                    </div>
                </div>

                {{-- OS em Execução --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            OS em Execução
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $osEmExecucao ?? 0 }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Técnicos atualmente trabalhando nessas ordens.
                        </p>
                    </div>
                </div>

                {{-- OS concluídas hoje --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            OS Concluídas Hoje
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $osConcluidasHoje ?? 0 }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Total de ordens finalizadas na data de hoje.
                        </p>
                    </div>
                </div>

                {{-- Clientes Ativos --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Clientes Ativos
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $clientesAtivos ?? 0 }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Clientes com contratos ou OS abertas.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Ações rápidas --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Ações rápidas
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Otimize seu tempo criando ordens e acessando os principais cadastros.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('ordens.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Nova OS
                        </a>
                        <a href="{{ route('clientes.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600">
                            Clientes
                        </a>
                        <a href="{{ route('equipamentos.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600">
                            Equipamentos
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tabela de últimas OS --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Últimas Ordens de Serviço
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Acompanhe rapidamente as últimas movimentações.
                    </p>
                </div>

                <div class="px-6 pb-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Previsão
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($ordens ?? [] as $os)
                                <tr>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->codigo ?? $os->id }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->cliente->nome ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @class([
                                                'bg-yellow-100 text-yellow-800' => $os->status === 'aberta',
                                                'bg-blue-100 text-blue-800' => $os->status === 'em_execucao',
                                                'bg-green-100 text-green-800' => $os->status === 'concluida',
                                                'bg-gray-100 text-gray-800' => ! in_array($os->status, ['aberta','em_execucao','concluida']),
                                            ])">
                                            {{ ucfirst(str_replace('_', ' ', $os->status ?? '')) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ optional($os->previsao_conclusao)->format('d/m/Y') ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right">
                                        <a href="{{ route('ordens.show', $os) }}"
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-xs font-semibold">
                                            Ver detalhes
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
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
</x-app-layout>
