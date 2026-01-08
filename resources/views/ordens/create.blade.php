<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nova Ordem de Serviço') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alertas --}}
            @if (session('success'))
                <div class="bg-verdes-verde_claro/15 border border-verdes-verde_claro/30 text-verdes-verde_escuro text-sm px-4 py-2 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Card do formulário --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-1 border-b border-gray-100 dark:border-white/10 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Dados da ordem
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Preencha os campos para abrir a ordem de serviço.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('ordens.store') }}"
                          enctype="multipart/form-data"
                          class="mt-6 space-y-6">
                        @csrf

                        {{-- Solicitante (usuário logado) --}}
                        <div class="rounded-lg border border-verdes-verde_claro/20 bg-verdes-verde_claro/5 p-3 dark:border-verdes-verde_claro/30 dark:bg-verdes-verde_claro/10">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                Solicitante
                            </p>
                            <p class="mt-0.5 text-sm text-gray-900 dark:text-gray-100">
                                {{ auth()->user()->name ?? auth()->user()->email }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            {{-- Setor --}}
                            <div>
                                <label for="setor_id"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Setor <span class="text-red-500">*</span>
                                </label>
                                <select id="setor_id" name="setor_id" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                               text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Selecione um setor</option>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}" @selected(old('setor_id') == $setor->id)>
                                            {{ $setor->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('setor_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Máquina --}}
                            <div>
                                <label for="equipamento_id"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Máquina / Equipamento <span class="text-red-500">*</span>
                                </label>
                                <select id="equipamento_id" name="equipamento_id" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                               text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="">Selecione um equipamento</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Selecione um setor para carregar os equipamentos.
                                </p>
                                @error('equipamento_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tipo + Prioridade --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Tipo --}}
                            <div>
                                <label for="tipo"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tipo <span class="text-red-500">*</span>
                                </label>
                                <select id="tipo" name="tipo" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                               text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="corretiva" @selected(old('tipo') === 'corretiva')>Corretiva</option>
                                    <option value="preventiva" @selected(old('tipo') === 'preventiva')>Preventiva</option>
                                </select>
                                @error('tipo')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Prioridade --}}
                            <div>
                                <label for="prioridade"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Urgência / Prioridade <span class="text-red-500">*</span>
                                </label>
                                <select id="prioridade" name="prioridade" required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                               text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                    <option value="baixo" @selected(old('prioridade') === 'baixo')>Baixo</option>
                                    <option value="medio" @selected(old('prioridade') === 'medio')>Médio</option>
                                    <option value="alto" @selected(old('prioridade') === 'alto')>Alto</option>
                                    <option value="muito_alto" @selected(old('prioridade') === 'muito_alto')>Muito Alto</option>
                                </select>
                                @error('prioridade')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Descrição --}}
                        <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/70 dark:bg-gray-900/40 p-4">
                            <label for="descricao"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Descrição do problema / serviço <span class="text-red-500">*</span>
                            </label>
                            <textarea id="descricao" name="descricao" rows="4" required
                                      class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                             text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                      placeholder="Descreva o problema, sintomas, local exato, etc.">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Anexos --}}
                        <div class="rounded-xl border border-dashed border-gray-300 dark:border-white/10 bg-gray-50/70 dark:bg-gray-900/40 p-4">
                            <label for="anexos"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Anexos (fotos, PDFs)
                            </label>
                            <input id="anexos" name="anexos[]" type="file" multiple
                                   accept="image/*,application/pdf"
                                   class="block w-full text-sm text-gray-900 dark:text-gray-100
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-verdes-verde_claro file:text-white
                                          hover:file:bg-verdes-verde_folha
                                          dark:file:bg-verdes-verde_claro dark:file:text-white
                                          dark:hover:file:bg-verdes-verde_folha">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Formatos permitidos: JPG, JPEG, PNG, PDF. Máx. 4 MB por arquivo.
                            </p>
                            @error('anexos.*')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botões --}}
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                            <a href="{{ auth()->check() && auth()->user()->hasRole('visualizador') ? route('dashboard.visualizador') : route('ordens.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                      text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </a>
                            
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                           text-xs font-semibold text-white uppercase tracking-widest
                                           hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                Salvar OS
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const setorSelect = document.getElementById('setor_id');
            const equipamentoSelect = document.getElementById('equipamento_id');
            const equipamentos = @json($equipamentos->map->only(['id', 'nome', 'setor_id']));

            const oldSetor = @json(old('setor_id'));
            const oldEquip = @json(old('equipamento_id'));

            const renderEquipamentos = (setorId) => {
                equipamentoSelect.innerHTML = '<option value="">Selecione um equipamento</option>';

                if (!setorId) {
                    equipamentoSelect.value = '';
                    equipamentoSelect.disabled = true;
                    return;
                }

                equipamentoSelect.disabled = false;

                const options = equipamentos.filter(eq => String(eq.setor_id) === String(setorId));

                options.forEach(eq => {
                    const opt = document.createElement('option');
                    opt.value = eq.id;
                    opt.textContent = eq.nome;
                    if (String(oldEquip) === String(eq.id)) {
                        opt.selected = true;
                    }
                    equipamentoSelect.appendChild(opt);
                });

                if (!equipamentoSelect.value && options.length) {
                    equipamentoSelect.value = options[0].id;
                }
            };

            setorSelect.addEventListener('change', (e) => {
                equipamentoSelect.value = '';
                renderEquipamentos(e.target.value);
            });

            renderEquipamentos(setorSelect.value || oldSetor);
        });
    </script>
</x-app-layout>
