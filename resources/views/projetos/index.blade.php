<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Projetos') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Cadastro e acompanhamento de projetos
                </h3>

                <a href="{{ route('projetos.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md
                          font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                    Novo projeto
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Projeto</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setor</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prazo</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($projetos as $proj)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $proj->titulo }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $proj->setor->nome ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $proj->prazo?->format('d/m/Y') ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border
                                            @class([
                                                'bg-emerald-50 text-emerald-700 border-emerald-200' => $proj->status === 'aberto',
                                                'bg-amber-50 text-amber-700 border-amber-200'       => $proj->status === 'em_andamento',
                                                'bg-sky-50 text-sky-700 border-sky-200'            => $proj->status === 'concluido',
                                                'bg-red-50 text-red-700 border-red-200'            => $proj->status === 'cancelado',
                                            ])">
                                            {{ Str::ucfirst(str_replace('_', ' ', $proj->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right">
                                        <a href="{{ route('projetos.show', $proj) }}"
                                           class="text-emerald-600 hover:text-emerald-900 text-xs font-semibold">
                                            Ver
                                        </a>
                                        <span class="mx-1 text-gray-400">|</span>
                                        <a href="{{ route('projetos.edit', $proj) }}"
                                           class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhum projeto cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $projetos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
