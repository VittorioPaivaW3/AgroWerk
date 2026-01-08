<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Projeto') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-6">
                    <div class="flex flex-col gap-1 border-b border-gray-100 dark:border-white/10 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Cadastro do projeto
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Preencha os dados do projeto.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('projetos.store') }}"
                          enctype="multipart/form-data"
                          class="mt-6 space-y-6">
                        @csrf

                        {{-- Setor --}}
                        <div>
                            <x-input-label for="setor_id" value="Setor" />
                            <select id="setor_id" name="setores_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                <option value="">Selecione um setor</option>
                                @foreach($setores as $setor)
                                    <option value="{{ $setor->id }}"
                                        @selected(old('setores_id') == $setor->id)>
                                        {{ $setor->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('setores_id')" class="mt-2" />
                        </div>

                        {{-- Título --}}
                        <div>
                            <x-input-label for="titulo" value="Título do projeto" />
                            <x-text-input id="titulo"
                                          name="titulo"
                                          type="text"
                                          class="mt-1 block w-full focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                          :value="old('titulo')" />
                            <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                        </div>

                        {{-- Prazo --}}
                        <div>
                            <x-input-label for="prazo" value="Prazo" />
                            <x-text-input id="prazo"
                                          name="prazo"
                                          type="date"
                                          class="mt-1 block w-full focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                          :value="old('prazo')" />
                            <x-input-error :messages="$errors->get('prazo')" class="mt-2" />
                        </div>

                        {{-- Orçamento previsto --}}
                        <div>
                            <x-input-label for="orcamento_previsto" value="Orçamento previsto" />
                            <x-text-input id="orcamento_previsto"
                                          name="orcamento_previsto"
                                          type="number"
                                          step="0.01"
                                          class="mt-1 block w-full focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                          :value="old('orcamento_previsto')"
                                          placeholder="0,00" />
                            <x-input-error :messages="$errors->get('orcamento_previsto')" class="mt-2" />
                        </div>

                        {{-- Orçamento real --}}
                        <div>
                            <x-input-label for="orcamento_real" value="Orçamento real (opcional)" />
                            <x-text-input id="orcamento_real"
                                          name="orcamento_real"
                                          type="number"
                                          step="0.01"
                                          class="mt-1 block w-full focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                          :value="old('orcamento_real')"
                                          placeholder="0,00" />
                            <x-input-error :messages="$errors->get('orcamento_real')" class="mt-2" />
                        </div>

                        {{-- Status --}}
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                <option value="aberto" @selected(old('status', 'aberto') === 'aberto')>Aberto</option>
                                <option value="em_andamento" @selected(old('status') === 'em_andamento')>Em andamento</option>
                                <option value="concluido" @selected(old('status') === 'concluido')>Concluído</option>
                                <option value="cancelado" @selected(old('status') === 'cancelado')>Cancelado</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        {{-- Descrição --}}
                        <div>
                            <x-input-label for="descricao" value="Descrição da demanda" />
                            <textarea id="descricao" name="descricao" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                             text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">{{ old('descricao') }}</textarea>
                            <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                        </div>

                        {{-- Orçamentos (anexos) --}}
                        <div>
                            <x-input-label for="orcamentos" value="Orçamentos / Anexos" />
                            <input id="orcamentos"
                                   name="orcamentos[]"
                                   type="file"
                                   multiple
                                   class="mt-1 block w-full text-sm
                                          text-gray-900 dark:text-gray-100
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-xs file:font-semibold
                                          file:bg-verdes-verde_claro file:text-white
                                          hover:file:bg-verdes-verde_folha dark:file:bg-verdes-verde_claro dark:file:text-white dark:hover:file:bg-verdes-verde_folha">
                            <p class="mt-1 text-xs text-gray-500">
                                Você pode enviar mais de um arquivo (PDF, JPG, PNG) – máximo 4MB cada.
                            </p>
                            <x-input-error :messages="$errors->get('orcamentos.*')" class="mt-2" />
                        </div>

                        {{-- Botões --}}
                        <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-gray-700 mt-4">
                            <a href="{{ route('projetos.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                      text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                           text-xs font-semibold text-white uppercase tracking-widest
                                           hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                Salvar projeto
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
