<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meu painel') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Cards de resumo --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                        Total de OS abertas por mim
                    </p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $stats['total'] }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                        Abertas
                    </p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600">
                        {{ $stats['abertas'] }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                        Em execução
                    </p>
                    <p class="mt-2 text-2xl font-bold text-amber-500">
                        {{ $stats['execucao'] }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                        Concluídas
                    </p>
                    <p class="mt-2 text-2xl font-bold text-sky-500">
                        {{ $stats['concluidas'] }}
                    </p>
                </div>
            </div>

            {{-- Tabela de OS do usuário --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Minhas solicitações de OS
                    </h3>

                    <a href="{{ route('ordens.create') }}"
                       class="inline-flex items-center px-3 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-md hover:bg-emerald-700">
                        Abrir nova OS
                    </a>
                </div>

                <div class="px-6 pb-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Setor
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Equipamento
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Criada em
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($ordens as $os)
                                @php
                                    $status = strtolower($os->status);
                                    $statusLabel = match ($status) {
                                        'aberta'       => 'Aberta',
                                        'em_execucao'  => 'Em execução',
                                        'concluida'    => 'Concluída',
                                        'cancelada'    => 'Cancelada',
                                        default        => ucfirst($status),
                                    };
                                    $temAtribuicoes = ($os->tecnicos_count ?? 0) > 0 || ($os->gestores_count ?? 0) > 0;
                                    $podeEditar = $status === 'aberta' && ! $temAtribuicoes;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        #{{ $os->codigo ?? $os->id }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $statusLabel }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->setor->nome ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->equipamento->nome ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->created_at?->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right space-x-2">
                                        <a href="{{ route('ordens.show', $os) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold
                                                  bg-indigo-600 text-white hover:bg-indigo-700">
                                            Ver
                                        </a>
                                        @if($podeEditar)
                                            <a href="{{ route('ordens.edit', $os) }}"
                                               class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold
                                                      bg-amber-600 text-white hover:bg-amber-700">
                                                Editar
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Você ainda não abriu nenhuma OS.
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
</x-app-layout>
