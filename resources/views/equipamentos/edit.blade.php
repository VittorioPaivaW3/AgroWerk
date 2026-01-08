<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Equipamento') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-1 border-b border-gray-100 dark:border-white/10 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Edicao do equipamento
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Atualize os dados do equipamento.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('equipamentos.update', $equipamento) }}"
                          enctype="multipart/form-data"
                          x-data='equipamentoForm(@json($equipamento->campos_extras))'
                          class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        {{-- Nome --}}
                        <div>
                            <label for="nome"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome do equipamento <span class="text-red-500">*</span>
                            </label>
                            <input id="nome" name="nome" type="text"
                                   value="{{ old('nome', $equipamento->nome) }}"
                                   required
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            @error('nome')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Codigo (opcional) --}}
                        <div>
                            <label for="codigo"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Codigo interno
                            </label>
                            <input id="codigo" name="codigo" type="text"
                                   value="{{ old('codigo', $equipamento->codigo) }}"
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
                                <option value="ativo"     @selected(old('status', $equipamento->status) === 'ativo')>Ativo</option>
                                <option value="inativo"   @selected(old('status', $equipamento->status) === 'inativo')>Inativo</option>
                                <option value="manutencao"@selected(old('status', $equipamento->status) === 'manutencao')>Em manutencao</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cor / Identificacao visual --}}
                        <div>
                            <label for="cor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Cor do equipamento
                            </label>

                            <div class="flex items-center gap-3">
                                <input
                                    id="cor"
                                    name="cor"
                                    type="color"
                                    value="{{ old('cor', $equipamento->cor ?? '#000000') }}"
                                    class="h-9 w-9 rounded-md border border-gray-300 dark:border-gray-700 bg-transparent p-0 cursor-pointer"
                                >

                                <input
                                    type="text"
                                    value="{{ old('cor', $equipamento->cor ?? '#000000') }}"
                                    x-data
                                    x-model="$el.previousElementSibling.value"
                                    readonly
                                    class="w-24 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        text-sm text-gray-900 dark:text-gray-100 px-2 py-1"
                                >
                            </div>

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
                                    <option value="{{ $setor->id }}"
                                        @selected(old('setor_id', $equipamento->setor_id) == $setor->id)>
                                        {{ $setor->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('setor_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Manutencao preventiva --}}
                        <div>
                            <label for="manutencao_preventiva"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data de manutencao preventiva
                            </label>
                            <input id="manutencao_preventiva" name="manutencao_preventiva" type="date"
                                value="{{ old('manutencao_preventiva', $equipamento->manutencao_preventiva
                                        ? \Illuminate\Support\Carbon::parse($equipamento->manutencao_preventiva)->format('Y-m-d')
                                        : null) }}"
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
                                    {{ old('terceiro', $equipamento->terceiro ?? false) ? 'checked' : '' }}
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    Equipamento de terceiros
                                </span>
                            </label>
                        </div>

                        {{-- Observacoes --}}
                        <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/70 dark:bg-gray-900/40 p-4">
                            <label for="observacoes"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Observacoes
                            </label>
                            <textarea id="observacoes" name="observacoes" rows="3"
                                      class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                             text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">{{ old('observacoes', $equipamento->observacoes) }}</textarea>
                            @error('observacoes')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Anexar arquivos --}}
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
                                Formatos permitidos: JPG, JPEG, PNG, PDF. Max. 4 MB por arquivo.
                            </p>
                            @error('anexos.*')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campos extras dinamicos --}}
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
                                Ex.: Numero de serie, Potencia (cv), Fabricante, etc.
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

                        {{-- Botoes --}}
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                            <a href="{{ route('equipamentos.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                      text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                           text-xs font-semibold text-white uppercase tracking-widest
                                           hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                Salvar alteracoes
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- Lista de anexos atuais (fora do form) --}}
            @if($equipamento->arquivos && $equipamento->arquivos->count())
                <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                    <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                    <div class="px-6 py-4 text-gray-900 dark:text-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                            Arquivos anexados
                        </h3>

                        <ul class="space-y-2">
                            @foreach($equipamento->arquivos as $arquivo)
                                @php
                                    $isPdf = str_contains($arquivo->mime_type ?? '', 'pdf');
                                @endphp

                                <li class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-md
                                                     bg-gray-100 dark:bg-gray-700 text-xs font-semibold">
                                            {{ $isPdf ? 'PDF' : 'IMG' }}
                                        </span>

                                        <a href="{{ route('equipamentos.arquivos.show', $arquivo) }}"
                                           target="_blank"
                                           class="text-verdes-verde_claro dark:text-verdes-verde_claro hover:text-verdes-verde_folha hover:underline">
                                            {{ $arquivo->nome_original ?? basename($arquivo->path) }}
                                        </a>

                                        @if($arquivo->size)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                ({{ number_format($arquivo->size / 1024, 1, ',', '.') }} KB)
                                            </span>
                                        @endif
                                    </div>

                                    <form method="POST"
                                          action="{{ route('equipamentos.arquivos.destroy', $arquivo) }}"
                                          onsubmit="return confirm('Remover este anexo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-semibold">
                                            Remover
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function equipamentoForm(initialExtras) {
            const camposIniciais = [];

            if (initialExtras) {
                if (Array.isArray(initialExtras)) {
                    // Formato lista [{campo/label/key, valor/value}] - preserva duplicados
                    initialExtras.forEach((item) => {
                        const key = item?.campo ?? item?.label ?? item?.key ?? '';
                        const value = item?.valor ?? item?.value ?? '';
                        if (key !== '' || value !== '') {
                            camposIniciais.push({ key, value });
                        }
                    });
                } else {
                    // Formato objeto associativo legado {campo: valor}
                    Object.entries(initialExtras).forEach(([key, value]) => {
                        camposIniciais.push({ key, value });
                    });
                }
            }

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
