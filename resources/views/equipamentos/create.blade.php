<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Equipamento') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('equipamentos.store') }}" enctype="multipart/form-data" x-data="equipamentoForm()">
                        @csrf

                        {{-- Nome --}}
                        <div class="mb-4">
                            <label for="nome"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome do equipamento <span class="text-red-500">*</span>
                            </label>
                            <input id="nome" name="nome" type="text"
                                   value="{{ old('nome') }}"
                                   required
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nome')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Código (opcional) --}}
                        <div class="mb-4">
                            <label for="codigo"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Código interno
                            </label>
                            <input id="codigo" name="codigo" type="text"
                                   value="{{ old('codigo') }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('codigo')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status
                            </label>
                            <select id="status" name="status"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="ativo"     @selected(old('status', 'ativo') === 'ativo')>Ativo</option>
                                <option value="inativo"   @selected(old('status') === 'inativo')>Inativo</option>
                                <option value="manutencao"@selected(old('status') === 'manutencao')>Em manutenção</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cor / Identificação visual --}}
                        <div class="mb-4">
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
                        <div class="mb-6">
                            <label for="setor_id"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Setor <span class="text-red-500">*</span>
                            </label>
                            <select id="setor_id" name="setor_id" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
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
                        <div class="mb-4">
                            <label for="manutencao_preventiva"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data de manutenção preventiva
                            </label>
                            <input id="manutencao_preventiva" name="manutencao_preventiva" type="date"
                                value="{{ old('manutencao_preventiva') }}"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('manutencao_preventiva')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Observações --}}
                        <div class="mb-6">
                            <label for="observacoes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Observações
                            </label>
                            <textarea id="observacoes" name="observacoes" rows="3"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                            text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Anexos (imagens / PDFs) --}}
                        <div class="mb-6">
                            <label for="anexos"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Anexos (imagens ou PDFs)
                            </label>

                            <input id="anexos" name="anexos[]" type="file" multiple
                                accept="image/*,.pdf"
                                class="block w-full text-sm text-gray-900 dark:text-gray-100
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-gray-700 dark:file:text-gray-200">

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Você pode selecionar múltiplos arquivos segurando CTRL (Windows) ou CMD (Mac).
                            </p>

                            @error('anexos.*')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror

                        {{-- Campos extras dinâmicos --}}
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Campos adicionais do equipamento
                                </h3>
                                <button type="button"
                                        @click="addCampo()"
                                        class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                                               text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                               hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                                        text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:flex-1">
                                <input
                                    type="text"
                                    x-model="campo.value"
                                    :name="`extra_values[${index}]`"
                                    placeholder="Valor"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
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
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                                      font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-200 dark:hover:bg-gray-600">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md
                                           font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
