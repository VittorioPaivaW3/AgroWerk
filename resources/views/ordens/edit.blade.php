<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar OS') }}
        </h2>
    </x-slot>

    @php
        // PRIORIDADE
        $prioridadeRaw = $ordem->prioridade ?? null;
        $prioridade = $prioridadeRaw ? strtolower(trim($prioridadeRaw)) : null;

        $prioridadeLabel = match ($prioridade) {
            'baixo'              => 'Baixo',
            'medio', 'médio'     => 'Médio',
            'alto'               => 'Alto',
            'muito_alto'         => 'Muito alto',
            default              => $prioridadeRaw ? ucfirst($prioridadeRaw) : '—',
        };

        $prioridadeBadgeClasses = match ($prioridade) {
            'baixo'              => 'bg-verdes-verde_claro/20 text-verdes-verde_escuro border-verdes-verde_claro/40',
            'medio', 'médio'     => 'bg-yellow-50 text-yellow-800 border-yellow-200',
            'alto'               => 'bg-orange-500/10 text-orange-700 border-orange-400',
            'muito_alto'         => 'bg-red-600 text-white border-red-700',
            default              => 'bg-gray-50 text-gray-700 border-gray-200',
        };

        // STATUS
        $statusRaw = $ordem->status ?? null;
        $status = $statusRaw ? strtolower(trim($statusRaw)) : null;

        $statusLabel = match ($status) {
            'aberta'       => 'Aberta',
            'em_execucao'  => 'Em execução',
            'concluida'    => 'Concluída',
            'cancelada'    => 'Cancelada',
            default        => $statusRaw ? ucfirst(str_replace('_', ' ', $statusRaw)) : '—',
        };

        $statusClasses = match ($status) {
            'aberta'       => 'bg-verdes-verde_claro/20 text-verdes-verde_escuro border-verdes-verde_claro/40',
            'em_execucao'  => 'bg-amber-50 text-amber-700 border-amber-200',
            'concluida'    => 'bg-verdes-verde_folha text-white border-verdes-verde_folha',
            'cancelada'    => 'bg-red-50 text-red-700 border-red-200',
            default        => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    @endphp

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Cabeçalho da OS --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-5 py-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <span>#{{ $ordem->codigo ?? $ordem->id }}</span>
                        </div>
                        <h1 class="mt-0.5 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Editar Ordem de Serviço
                        </h1>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-1">
                            <span>Criada em {{ $ordem->created_at?->format('d/m/Y H:i') ?? '—' }}</span>
                            @if($ordem->updated_at)
                                <span>·</span>
                                <span>Última atualização {{ $ordem->updated_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2 md:justify-end">
                        {{-- Status atual --}}
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $statusClasses }}">
                            <span class="h-2 w-2 rounded-full
                                @if($status === 'aberta') bg-verdes-verde_claro
                                @elseif($status === 'em_execucao') bg-amber-500
                                @elseif($status === 'concluida') bg-verdes-verde_folha
                                @elseif($status === 'cancelada') bg-red-500
                                @else bg-gray-400 @endif">
                            </span>
                            {{ $statusLabel }}
                        </span>

                        {{-- Prioridade atual --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold border {{ $prioridadeBadgeClasses }}">
                            @if($prioridade === 'muito_alto')
                                🔥
                            @elseif($prioridade === 'alto')
                                ⚠️
                            @elseif($prioridade === 'medio' || $prioridade === 'médio')
                                ⬆️
                            @elseif($prioridade === 'baixo')
                                ⬇️
                            @else
                                •
                            @endif
                            {{ strtoupper($prioridadeLabel) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- FORMULÁRIO DE EDIÇÃO --}}
            <form method="POST"
                  action="{{ route('ordens.update', $ordem) }}"
                  enctype="multipart/form-data"
                  class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                @csrf
                @method('PUT')

                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-5 py-5 space-y-6">

                    {{-- Linha 1: Status / Tipo / Prioridade --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Status --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                Status
                            </label>
                            <select name="status"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-verdes-verde_claro focus:border-verdes-verde_claro">
                                @php
                                    $statusOptions = [
                                        'aberta'      => 'Aberta',
                                        'em_execucao' => 'Em execução',
                                        'concluida'   => 'Concluída',
                                        'cancelada'   => 'Cancelada',
                                    ];
                                    $statusValue = old('status', $ordem->status);
                                @endphp
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected($statusValue === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                Tipo de OS
                            </label>
                            @php
                                $tipoValue = old('tipo', $ordem->tipo);
                            @endphp
                            <select name="tipo"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-verdes-verde_claro focus:border-verdes-verde_claro">
                                <option value="">Selecione...</option>
                                <option value="corretiva" @selected($tipoValue === 'corretiva')>Corretiva</option>
                                <option value="preventiva" @selected($tipoValue === 'preventiva')>Preventiva</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Prioridade --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                Prioridade
                            </label>
                            @php
                                $prioridadeValue = old('prioridade', $ordem->prioridade);
                            @endphp
                            <select name="prioridade"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-verdes-verde_claro focus:border-verdes-verde_claro">
                                <option value="">Selecione...</option>
                                <option value="baixo" @selected($prioridadeValue === 'baixo')>Baixo</option>
                                <option value="medio" @selected(in_array($prioridadeValue, ['medio','médio']))>Médio</option>
                                <option value="alto" @selected($prioridadeValue === 'alto')>Alto</option>
                                <option value="muito_alto" @selected($prioridadeValue === 'muito_alto')>Muito alto</option>
                            </select>
                            @error('prioridade')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Linha 2: Setor / Equipamento --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Setor --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                Setor
                            </label>

                            @isset($setores)
                                @php
                                    $setorIdValue = old('setor_id', $ordem->setor_id);
                                @endphp
                                <select name="setor_id"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-verdes-verde_claro focus:border-verdes-verde_claro">
                                    <option value="">Selecione...</option>
                                    @foreach($setores as $setor)
                                        <option value="{{ $setor->id }}" @selected($setorIdValue == $setor->id)>
                                            {{ $setor->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $ordem->setor->nome ?? '—' }}
                                </p>
                            @endisset

                            @error('setor_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Equipamento --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                Equipamento / Ativo
                            </label>

                            @isset($equipamentos)
                                @php
                                    $equipIdValue = old('equipamento_id', $ordem->equipamento_id);
                                @endphp
                                <select name="equipamento_id"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-verdes-verde_claro focus:border-verdes-verde_claro">
                                    <option value="">Selecione...</option>
                                    @foreach($equipamentos as $equip)
                                        <option value="{{ $equip->id }}" @selected($equipIdValue == $equip->id)>
                                            {{ $equip->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $ordem->equipamento->nome ?? '—' }}
                                </p>
                            @endisset

                            @error('equipamento_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Descrição --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                            Descrição do problema / solicitação
                        </label>
                        <textarea
                            name="descricao"
                            rows="4"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm focus:ring-verdes-verde_claro focus:border-verdes-verde_claro">{{ old('descricao', $ordem->descricao) }}</textarea>
                        @error('descricao')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Anexos: novos + lista atuais --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Upload novos --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                Adicionar anexos
                            </label>
                            <input type="file"
                                   name="anexos[]"
                                   multiple
                                   accept=".pdf,.png,.jpg,.jpeg"
                                   class="block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-verdes-verde_claro file:text-white hover:file:bg-verdes-verde_folha dark:file:bg-verdes-verde_claro dark:file:text-white dark:hover:file:bg-verdes-verde_folha">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Você pode selecionar múltiplos arquivos (imagens, PDFs, etc).
                            </p>
                            @error('anexos.*')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Anexos atuais --}}
                        <div>
                            <p class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                Anexos atuais
                            </p>

                            @if($ordem->anexos && $ordem->anexos->count())
                                <ul class="space-y-2 text-sm">
                                    @foreach($ordem->anexos as $anexo)
                                        @php
                                            $rawPath = $anexo->path ?? $anexo->caminho ?? '';
                                            $path = str_replace('\\', '/', $rawPath);
                                            if (str_starts_with($path, 'public/')) {
                                                $path = substr($path, strlen('public/'));
                                            }
                                            $url = $path ? \Illuminate\Support\Facades\Storage::disk('public')->url($path) : '#';
                                        @endphp
                                        <li class="flex items-center justify-between gap-2">
                                            <span class="truncate max-w-xs text-gray-900 dark:text-gray-100">
                                                {{ $anexo->nome_original }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ $url }}"
                                                   target="_blank"
                                                   class="text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-[11px] font-semibold">
                                                    Abrir
                                                </a>
                                                <a href="{{ $url }}"
                                                   download
                                                   class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white text-[11px]">
                                                    Baixar
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Nenhum anexo enviado para esta OS.
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Ações --}}
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('ordens.show', $ordem) }}"
                           class="inline-flex items-center px-3.5 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md
                                  text-[11px] font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                  hover:bg-gray-200 dark:hover:bg-gray-700">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                       text-[11px] font-semibold text-white uppercase tracking-widest
                                       hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Salvar alterações
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
