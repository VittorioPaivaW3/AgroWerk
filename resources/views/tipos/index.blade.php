<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tipos de Equipamento') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Tipos cadastrados
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Gerencie os tipos de equipamento para padronizar o cadastro.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('tipos.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest
                                  hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Adicionar Tipo
                        </a>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-verdes-verde_claro/10 dark:bg-verdes-verde_claro/10">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Codigo
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Equipamentos
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Descricao
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Acoes
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($tipos as $tipo)
                                <tr class="hover:bg-verdes-verde_claro/5 dark:hover:bg-verdes-verde_claro/10 transition">
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $tipo->codigo ?? $tipo->id }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $tipo->nome }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $tipo->equipamentos_count }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $tipo->descricao ?: '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right space-x-2">
                                        <div x-data="{ open: false }" class="inline">
                                            <button
                                                type="button"
                                                @click="open = true"
                                                class="text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-xs font-semibold"
                                            >
                                                Editar
                                            </button>

                                            <div
                                                x-show="open"
                                                class="fixed inset-0 z-50 flex items-center justify-center"
                                                style="display: none;"
                                            >
                                                <div class="fixed inset-0 bg-black/50" @click="open = false"></div>

                                                <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-white/10 dark:bg-gray-800 max-w-md w-full mx-4">
                                                    <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                                                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                                            Editar tipo
                                                        </h3>
                                                        <button type="button" @click="open = false" class="text-gray-400 hover:text-verdes-verde_folha">
                                                            x
                                                        </button>
                                                    </div>

                                                    <div class="px-6 py-4">
                                                        <form method="POST" action="{{ route('tipos.update', $tipo) }}">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="mb-3 text-left">
                                                                <label for="nome-{{ $tipo->id }}"
                                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                    Nome <span class="text-red-500">*</span>
                                                                </label>
                                                                <input id="nome-{{ $tipo->id }}" name="nome" type="text"
                                                                    value="{{ old('nome', $tipo->nome) }}"
                                                                    required
                                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                                           text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                                            </div>

                                                            <div class="mb-3 text-left">
                                                                <label for="codigo-{{ $tipo->id }}"
                                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                    Codigo (opcional)
                                                                </label>
                                                                <input id="codigo-{{ $tipo->id }}" name="codigo" type="text"
                                                                    value="{{ old('codigo', $tipo->codigo) }}"
                                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                                           text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                                            </div>

                                                            <div class="mb-4 text-left">
                                                                <label for="descricao-{{ $tipo->id }}"
                                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                    Descricao
                                                                </label>
                                                                <textarea id="descricao-{{ $tipo->id }}" name="descricao" rows="3"
                                                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                                               text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">{{ old('descricao', $tipo->descricao) }}</textarea>
                                                            </div>

                                                            <div class="flex justify-end gap-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                                                <button type="button"
                                                                        @click="open = false"
                                                                        class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                                                                               font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                                                               hover:bg-gray-200 dark:hover:bg-gray-600">
                                                                    Cancelar
                                                                </button>

                                                                <button type="submit"
                                                                        class="inline-flex items-center px-3 py-1.5 bg-verdes-verde_claro border border-transparent rounded-md
                                                                               font-semibold text-xs text-white uppercase tracking-widest
                                                                               hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                                                    Salvar
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <form method="POST"
                                              action="{{ route('tipos.destroy', $tipo) }}"
                                              class="inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este tipo?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-semibold">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhum tipo cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $tipos->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

