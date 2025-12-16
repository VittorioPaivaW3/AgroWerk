<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Manutenção Preventiva') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('manutencoes.preventivas.update', $manutencao) }}">
                        @csrf
                        @method('PUT')

                        {{-- Equipamento --}}
                        <div class="mb-4">
                            <label for="equipamento_id"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Equipamento <span class="text-red-500">*</span>
                            </label>
                            <select id="equipamento_id" name="equipamento_id" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecione um equipamento</option>
                                @foreach($equipamentos as $equipamento)
                                    <option value="{{ $equipamento->id }}"
                                        @selected(old('equipamento_id', $manutencao->equipamento_id) == $equipamento->id)>
                                        {{ $equipamento->nome }}
                                        @if($equipamento->setor) - {{ $equipamento->setor->nome }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('equipamento_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Data prevista --}}
                        <div class="mb-4">
                            <label for="data_prevista"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data prevista
                            </label>
                            <input type="date"
                                   id="data_prevista"
                                   name="data_prevista"
                                   value="{{ old('data_prevista', $manutencao->data_prevista
                                        ? \Illuminate\Support\Carbon::parse($manutencao->data_prevista)->format('Y-m-d')
                                        : '') }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('data_prevista')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="status"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status
                            </label>
                            @php
                                $statusAtual = old('status', $manutencao->status ?? 'pendente');
                            @endphp
                            <select id="status" name="status"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pendente"  @selected($statusAtual === 'pendente')>Pendente</option>
                                <option value="concluida" @selected($statusAtual === 'concluida')>Concluída</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descrição --}}
                        <div class="mb-6">
                            <label for="descricao"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                O que deve ser feito? <span class="text-red-500">*</span>
                            </label>
                            <textarea id="descricao" name="descricao" rows="4" required
                                      class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                             text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Ex.: Verificar rolamentos da esteira, lubrificar correntes, apertar parafusos, etc.">{{ old('descricao', $manutencao->descricao) }}</textarea>
                            @error('descricao')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botões --}}
                        <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                            <a href="{{ route('manutencoes.preventivas.show', $manutencao) }}"
                               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                      text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md
                                           text-xs font-semibold text-white uppercase tracking-widest
                                           hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Salvar alterações
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
