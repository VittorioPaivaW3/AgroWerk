<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Setores') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Topo: título + botão --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Setores cadastrados
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Gerencie os setores utilizados nos equipamentos e ordens de serviço.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('setores.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest
                                  hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Adicionar Setor
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tabela de setores --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-verdes-verde_claro/10 dark:bg-verdes-verde_claro/10">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Descrição
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($setores as $setor)
                                <tr class="hover:bg-verdes-verde_claro/5 dark:hover:bg-verdes-verde_claro/10 transition">
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $setor->codigo ?? $setor->id }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $setor->nome }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $setor->descricao ?: '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right space-x-2">
                                        {{-- Editar em modal --}}
                                        <div x-data="{ open: false }" class="inline">
                                            <button
                                                type="button"
                                                @click="open = true"
                                                class="text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-xs font-semibold"
                                            >
                                                Editar
                                            </button>

                                            {{-- Modal --}}
                                            <div
                                                x-show="open"
                                                class="fixed inset-0 z-50 flex items-center justify-center"
                                                style="display: none;"
                                            >
                                                {{-- Fundo escuro --}}
                                                <div class="fixed inset-0 bg-black/50" @click="open = false"></div>

                                                {{-- Caixa do modal --}}
                                                <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-white/10 dark:bg-gray-800 max-w-md w-full mx-4">
                                                    <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                                                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                                            Editar setor
                                                        </h3>
                                                        <button type="button" @click="open = false" class="text-gray-400 hover:text-verdes-verde_folha">
                                                            x
                                                        </button>
                                                    </div>

                                                    <div class="px-6 py-4">
                                                        <form method="POST" action="{{ route('setores.update', $setor) }}">
                                                            @csrf
                                                            @method('PUT')

                                                            {{-- Nome --}}
                                                            <div class="mb-3 text-left">
                                                                <label for="nome-{{ $setor->id }}"
                                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                    Nome do setor <span class="text-red-500">*</span>
                                                                </label>
                                                                <input id="nome-{{ $setor->id }}" name="nome" type="text"
                                                                    value="{{ old('nome', $setor->nome) }}"
                                                                    required
                                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                                            text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                                            </div>

                                                            {{-- Código --}}
                                                            <div class="mb-3 text-left">
                                                                <label for="codigo-{{ $setor->id }}"
                                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                    Código (opcional)
                                                                </label>
                                                                <input id="codigo-{{ $setor->id }}" name="codigo" type="text"
                                                                    value="{{ old('codigo', $setor->codigo) }}"
                                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                                            text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                                            </div>

                                                            {{-- Descrição --}}
                                                            <div class="mb-4 text-left">
                                                                <label for="descricao-{{ $setor->id }}"
                                                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                    Descrição
                                                                </label>
                                                                <textarea id="descricao-{{ $setor->id }}" name="descricao" rows="3"
                                                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                                                text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">{{ old('descricao', $setor->descricao) }}</textarea>
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

                                        {{-- Excluir continua normal --}}
                                        <form method="POST"
                                            action="{{ route('setores.destroy', $setor) }}"
                                            class="inline"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este setor?');">
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
                                    <td colspan="4" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhum setor cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $setores->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
