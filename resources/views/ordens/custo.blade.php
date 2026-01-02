<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Atribuir custo à OS') }} #{{ $ordem->codigo ?? $ordem->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="px-6 py-5 space-y-4">

                    {{-- Resumo rápido da OS --}}
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Setor: <span class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $ordem->setor->nome ?? '—' }}
                            </span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Equipamento:
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $ordem->equipamento->nome ?? '—' }}
                            </span>
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Status:
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ strtoupper($ordem->status) }}
                            </span>
                        </p>
                    </div>

                    {{-- Formulário de custo --}}
                    <form method="POST" action="{{ route('ordens.custo.update', $ordem) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-2">
                            <label for="custo_total"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Custo total da OS
                            </label>
                            <div class="relative rounded-md shadow-sm max-w-xs">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm">
                                    R$
                                </span>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="custo_total"
                                    id="custo_total"
                                    value="{{ old('custo_total', $ordem->custo_total) }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md
                                           bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100
                                           focus:ring-emerald-500 focus:border-emerald-500"
                                    required
                                >
                            </div>
                            @error('custo_total')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <a href="{{ route('ordens.show', $ordem) }}"
                               class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                Voltar para detalhes da OS
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md
                                           font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700
                                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Salvar custo
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
