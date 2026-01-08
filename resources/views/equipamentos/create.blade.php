<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Equipamento') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-1 border-b border-gray-100 dark:border-white/10 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Cadastro do equipamento
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Preencha os dados abaixo para cadastrar o equipamento.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('equipamentos.store') }}" enctype="multipart/form-data" x-data="equipamentoForm()" class="mt-6 space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        {{-- Nome --}}
                        <div>
                            <label for="nome"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome do equipamento <span class="text-red-500">*</span>
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

                        {{-- Código (opcional) --}}
                        <div>
                            <label for="codigo"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Código interno
                            </label>
                            <input id="codigo" name="codigo" type="text"
                                   value="{{ old('codigo') }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            @error('codigo')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status
                            </label>
                            <select id="status" name="status"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                <option value="ativo"     @selected(old('status', 'ativo') === 'ativo')>Ativo</option>
                                <option value="inativo"   @selected(old('status') === 'inativo')>Inativo</option>
                                <option value="manutencao"@selected(old('status') === 'manutencao')>Em manutenção</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cor / Identificação visual --}}
                        <div>
                            <label for="cor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Cor do equipamento
                            </label>

                            <div class="flex items-center gap-3">
                                {{-- Se não houver old(), usa um padrão (#000000) --}}
                                <input
                                    id="cor"
                                    name="cor"
                                    type="color"
                                    value="{{ old('cor', '#000000') }}"
                                    class="h-9 w-9 rounded-md border border-gray-300 dark:border-gray-700 bg-transparent p-0 cursor-pointer"
                                >

                                <input
                                    type="text"
                                    value="{{ old('cor', '#000000') }}"
                                    x-data
                                    x-model="$el.previousElementSibling.value"
                                    readonly
                                    class="w-24 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 px-2 py-1"
                                >
                            </div>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Essa cor será usada futuramente para identificar rapidamente o equipamento no sistema.
                            </p>

                            @error('cor')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

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
                                @foreach ($setores as $setor)
                                    <option value="{{ $setor->id }}" @selected(old('setor_id') == $setor->id)>
                                        {{ $setor->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('setor_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Manutenção preventiva --}}
                        <div>
                            <label for="manutencao_preventiva"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data de manutenção preventiva
                            </label>
                            <input id="manutencao_preventiva" name="manutencao_preventiva" type="date"
                                value="{{ old('manutencao_preventiva') }}"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            @error('manutencao_preventiva')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        </div>

                        {{-- Equipamento de terceiros --}}
                        <div class="rounded-lg border border-verdes-verde_claro/20 bg-verdes-verde_claro/5 p-3 dark:border-verdes-verde_claro/30 dark:bg-verdes-verde_claro/10">
                            <label class="inline-flex items-center">
                                <input
                                    type="checkbox"
                                    name="terceiro"
                                    value="1"
                                    class="h-4 w-4 rounded border-gray-300 text-verdes-verde_claro shadow-sm focus:ring-verdes-verde_claro/40"
                                    {{ old('terceiro') ? 'checked' : '' }}
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    Equipamento de terceiros
                                </span>
                            </label>
                        </div>

                        {{-- Observações --}}
                        <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/70 dark:bg-gray-900/40 p-4">
                            <label for="observacoes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Observações
                            </label>
                            <textarea id="observacoes" name="observacoes" rows="3"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                            text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Anexos (imagens / PDFs) --}}
                        <div class="rounded-xl border border-dashed border-gray-300 dark:border-white/10 bg-gray-50/70 dark:bg-gray-900/40 p-4">
                            <label for="anexos"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Anexos (imagens ou PDFs)
                            </label>

                            <input id="anexos" name="anexos[]" type="file" multiple
                                accept=".pdf,.png,.jpg,.jpeg"
                                class="block w-full text-sm text-gray-900 dark:text-gray-100
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-verdes-verde_claro file:text-white
                                        hover:file:bg-verdes-verde_folha
                                        dark:file:bg-verdes-verde_claro dark:file:text-white
                                        dark:hover:file:bg-verdes-verde_folha">

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Você pode selecionar múltiplos arquivos segurando CTRL (Windows) ou CMD (Mac).
                            </p>

                            @error('anexos.*')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campos extras dinâmicos --}}
                        <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/70 dark:bg-gray-900/40 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Campos adicionais do equipamento
                                </h3>
                                <button type="button"
                                        @click="addCampo()"
                                        class="inline-flex items-center px-3 py-1.5 bg-verdes-verde_claro border border-transparent rounded-md
                                               text-xs font-semibold text-white uppercase tracking-widest
                                               hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                    + Adicionar campo
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                Ex.: “Número de série”, “Potência (cv)”, “Fabricante”, etc.
                            </p>

                   <template x-for="(campo, index) in campos" :key="index">
                        <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-center">
                            <div class="md:w-5/12">
                                <input
                                    type="text"
                                    x-model="campo.key"
                                    :name="`extra_keys[${index}]`"
                                    placeholder="Nome do campo"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>
                            <div class="md:flex-1">
                                <input
                                    type="text"
                                    x-model="campo.value"
                                    :name="`extra_values[${index}]`"
                                    placeholder="Valor"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>
                            <div class="md:w-auto flex justify-end">
                                <button type="button"
                                        @click="removeCampo(index)"
                                        class="inline-flex items-center px-2 py-1 text-xs text-red-500 hover:text-red-700">
                                    Remover
                                </button>
                            </div>
                        </div>
                    </template>
                        </div>

                        {{-- Botões --}}
                        <div class="flex justify-end gap-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <a href="{{ route('equipamentos.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                      font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                           font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                Salvar equipamento
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function equipamentoForm() {
            return {
                campos: [
                    { key: '', value: '' },
                ],
                addCampo() {
                    this.campos.push({ key: '', value: '' });
                },
                removeCampo(index) {
                    this.campos.splice(index, 1);
                    if (this.campos.length === 0) {
                        this.campos.push({ key: '', value: '' });
                    }
                },
            }
        }
    </script>

    <script>
    function equipamentoForm(initialExtras = {}) {
        const camposIniciais = [];

        // Se vier como objeto {campo: valor, ...}
        if (initialExtras && !Array.isArray(initialExtras)) {
            for (const [key, value] of Object.entries(initialExtras)) {
                camposIniciais.push({ key, value });
            }
        }

        // Se vier vazio, garante pelo menos uma linha
        if (camposIniciais.length === 0) {
            camposIniciais.push({ key: '', value: '' });
        }

        return {
            campos: camposIniciais,
            addCampo() {
                this.campos.push({ key: '', value: '' });
            },
            removeCampo(index) {
                this.campos.splice(index, 1);
                if (this.campos.length === 0) {
                    this.campos.push({ key: '', value: '' });
                }
            },
        }
    }
</script>
</x-app-layout>
