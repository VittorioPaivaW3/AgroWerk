<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Setor') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-1 border-b border-gray-100 dark:border-white/10 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Cadastro do setor
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Informe os dados basicos do setor.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('setores.store') }}" class="mt-6 space-y-6">
                        @csrf

                        {{-- Nome --}}
                        <div class="mb-4">
                            <label for="nome"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome do setor <span class="text-red-500">*</span>
                            </label>
                            <input id="nome" name="nome" type="text"
                                   value="{{ old('nome') }}"
                                   required
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            @error('nome')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Código --}}
                        <div class="mb-4">
                            <label for="codigo"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Código (opcional)
                            </label>
                            <input id="codigo" name="codigo" type="text"
                                   value="{{ old('codigo') }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            @error('codigo')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descrição --}}
                        <div class="mb-6">
                            <label for="descricao"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Descrição
                            </label>
                            <textarea id="descricao" name="descricao" rows="3"
                                      class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                             text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botões --}}
                        <div class="flex justify-end gap-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <a href="{{ route('setores.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                                      font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-200 dark:hover:bg-gray-600">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                           font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                Salvar setor
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
