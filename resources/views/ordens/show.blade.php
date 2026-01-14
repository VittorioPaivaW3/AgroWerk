<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes da OS') }}
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
            'baixo'              => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'medio', 'médio'     => 'bg-yellow-50 text-yellow-800 border-yellow-200',
            'alto'               => 'bg-orange-500/10 text-orange-700 border-orange-400',
            'muito_alto'         => 'bg-red-600 text-white border-red-700',
            default              => 'bg-gray-50 text-gray-700 border-gray-200',
        };

        // Borda lateral do card principal pela prioridade
        $cardPriorityBorder = match ($prioridade) {
            'muito_alto' => 'border-l-4 border-red-600',
            'alto'       => 'border-l-4 border-orange-500',
            'medio', 'médio' => 'border-l-4 border-yellow-400',
            'baixo'      => 'border-l-4 border-emerald-500',
            default      => 'border-l-4 border-gray-200',
        };
        $priorityBadgeClass = match ($prioridade) {
            'muito_alto', 'alto' => 'bg-red-500',
            'medio'              => 'bg-yellow-400',
            'baixo'              => 'bg-verdes-verde_claro',
            default              => 'bg-gray-200',
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
            'aberta'       => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'em_execucao'  => 'bg-amber-50 text-amber-700 border-amber-200',
            'concluida'    => 'bg-sky-50 text-sky-700 border-sky-200',
            'cancelada'    => 'bg-red-50 text-red-700 border-red-200',
            default        => 'bg-gray-50 text-gray-700 border-gray-200',
        };

        $statusIconLight = match ($status) {
            'aberta'       => 'imagem/engrenagem_alerta.png',
            'em_execucao'  => 'imagem/engrenagem_play.png',
            'concluida'    => 'imagem/engrenagem.png',
            'cancelada'    => 'imagem/engrenagem_alerta.png',
            default        => 'imagem/engrenagem_alerta.png',
        };

        $statusIconDark = match ($status) {
            'aberta'       => 'imagem/engrenagem_alerta_white.png',
            'em_execucao'  => 'imagem/engrenagem_play_white.png',
            'concluida'    => 'imagem/engrenagem_white.png',
            'cancelada'    => 'imagem/engrenagem_alerta_white.png',
            default        => 'imagem/engrenagem_alerta_white.png',
        };

        // TIPO
        $tipoRaw = $ordem->tipo ?? null;
        $tipo = $tipoRaw ? strtolower(trim($tipoRaw)) : null;

        $tipoLabel = match ($tipo) {
            'corretiva'  => 'Corretiva',
            'preventiva' => 'Preventiva',
            default      => $tipoRaw ? ucfirst($tipoRaw) : '—',
        };

        // Setor (direto ou via equipamento)
        $setorNome = $ordem->setor->nome
            ?? $ordem->equipamento->setor->nome
            ?? '—';
    @endphp

    @php
        $user = auth()->user();
        $ehTecnicoDaOrdem = $user
            ? $ordem->tecnicos->contains('id', $user->id)
            : false;
    @endphp

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- CARD PRINCIPAL (cabeçalho + status + prioridade) --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg {{ $cardPriorityBorder }}">
                <div class="px-5 py-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-md {{ $priorityBadgeClass }}"
                                  title="Status {{ $statusLabel }} / Prioridade {{ strtoupper($prioridadeLabel) }}"
                                  aria-label="Status {{ $statusLabel }} / Prioridade {{ strtoupper($prioridadeLabel) }}">
                                <img src="{{ asset($statusIconLight) }}"
                                     alt="Status {{ $statusLabel }}"
                                     class="h-5 w-5 object-contain dark:hidden">
                                <img src="{{ asset($statusIconDark) }}"
                                     alt="Status {{ $statusLabel }}"
                                     class="hidden h-5 w-5 object-contain dark:block">
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">#{{ $ordem->codigo ?? $ordem->id }}</span>
                        </div>
                        <h1 class="mt-0.5 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Detalhes da Solicitação
                        </h1>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-1">
                            <span>Criada em {{ $ordem->created_at?->format('d/m/Y H:i') ?? '—' }}</span>
                            @if($ordem->updated_at)
                                <span>·</span>
                                <span>Última atualização {{ $ordem->updated_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </p>
                         @if($ordem->inicio_execucao_em)
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-1">
                                <span>Iniciada em {{ $ordem->inicio_execucao_em->format('d/m/Y H:i') }}</span>

                                @if($ordem->fim_execucao_em)
                                    <span>·</span>
                                    <span>Concluída em {{ $ordem->fim_execucao_em->format('d/m/Y H:i') }}</span>
                                @endif
                            </p>
                        @endif
                    </div>

                </div>
            </div>

{{-- GRID PRINCIPAL: Detalhes | Sobre o problema | Anexos --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    {{-- Coluna 1: Detalhes da OS --}}
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="px-5 py-4 space-y-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                    Detalhes da OS
                </h3>

                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            Solicitante
                        </p>
                        <p class="text-gray-900 dark:text-gray-100">
                            {{ $ordem->solicitante->name ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            Equipamento / Ativo
                        </p>
                        <p class="text-gray-900 dark:text-gray-100">
                            @if($ordem->equipamento)
                                <a href="{{ route('equipamentos.show', $ordem->equipamento) }}"
                                   class="text-emerald-600 dark:text-emerald-400 hover:underline">
                                    {{ $ordem->equipamento->nome }}
                                </a>
                            @else
                                —
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            Setor
                        </p>
                        <p class="text-gray-900 dark:text-gray-100">
                            {{ $setorNome }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            Tipo de OS
                        </p>
                        <p class="text-gray-900 dark:text-gray-100">
                            {{ $tipoLabel }}
                        </p>
                    </div>

                    @if($ordem->concluida_por_terceiros)
                        <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                            Concluída por responsabilidade de <strong>terceiros</strong>.
                        </p>
                    @endif
                    
                    <div>
                        <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            Custo de Equipamento
                        </p>
                        <p class="text-gray-900 dark:text-gray-100">
                            @if(!is_null($ordem->custo_total))
                                R$ {{ number_format($ordem->custo_total, 2, ',', '.') }}
                            @else
                                —
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            Técnicos
                        </p>
                        <p class="mt-0.5 text-sm text-gray-900 dark:text-gray-100 flex flex-wrap gap-1">
                            @forelse($ordem->tecnicos as $tec)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    {{ $tec->name }}
                                </span>
                            @empty
                                —
                            @endforelse
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            Gestores
                        </p>
                        <p class="mt-0.5 text-sm text-gray-900 dark:text-gray-100 flex flex-wrap gap-1">
                            @forelse($ordem->gestores as $gestor)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-sky-50 text-sky-700 border border-sky-100">
                                    {{ $gestor->name }}
                                </span>
                            @empty
                                —
                            @endforelse
                        </p>
                    </div>
                    {{-- Tempo de execução --}}
                        @if(!is_null($ordem->duracao_execucao_em_horas))
                            <div>
                                <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                                    Tempo de execução
                                </p>
                                <p class="text-gray-900 dark:text-gray-100">
                                    {{ number_format($ordem->duracao_execucao_em_horas, 2, ',', '.') }} h
                                </p>
                            </div>
                        @endif

                        {{-- Custo de mão de obra --}}
                        @if(!is_null($ordem->custo_mao_de_obra))
                            <div>
                                <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                                    Custo de mão de obra
                                </p>
                                <p class="text-gray-900 dark:text-gray-100 font-semibold">
                                    R$ {{ number_format($ordem->custo_mao_de_obra, 2, ',', '.') }}
                                </p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                    Somatório das horas de execução x valor/hora de cada técnico
                                </p>
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Coluna 2: Sobre o problema --}}
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="px-5 py-4 space-y-2">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Sobre o problema
                </h3>
                <div class="rounded-md border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-3 py-3">
                    <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">
                        {{ $ordem->descricao ?: 'Nenhuma descrição informada.' }}
                    </p>
                </div>
                @if($ordem->observacao_conclusao)
                    <div class="mt-3">
                        <p class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                            Observacao da conclusao
                        </p>
                        <div class="mt-1 rounded-md border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/60 px-3 py-3">
                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">
                                {{ $ordem->observacao_conclusao }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Coluna 3: Anexos --}}
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="px-5 py-4 space-y-2">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Anexos
                    </h3>
                    @if($ordem->anexos && $ordem->anexos->count())
                        <span class="text-[11px] text-gray-400 dark:text-gray-500">
                            {{ $ordem->anexos->count() }} arquivo(s)
                        </span>
                    @endif
                </div>

                @if($ordem->anexos && $ordem->anexos->count())
                    <div class="space-y-3">
                    @foreach ($ordem->anexos as $anexo)
                        @php
                            $ext = strtolower(pathinfo($anexo->nome_original, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']);

                            // Caminho que veio do banco (preferindo 'path', mas aceitando 'caminho' legado)
                            $rawPath = $anexo->path ?? $anexo->caminho ?? '';

                            // Remove "public/" ou "public\" do começo, se existir
                            $relativePath = ltrim(str_replace(['public/', 'public\\'], '', $rawPath), '/\\');

                            // Monta URL final: /storage/ordens/anexos/arquivo.png
                            $url = $relativePath ? asset('storage/' . $relativePath) : '#';
                        @endphp

                        <div class="border border-gray-100 dark:border-gray-700 rounded-md p-3 flex flex-col gap-2">
                            <div class="flex items-start justify-between gap-3 text-sm">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 text-xs">
                                        {{ strtoupper($ext) }}
                                    </span>
                                    <div class="flex flex-col leading-tight min-w-0">
                                        <span class="text-gray-900 dark:text-gray-100 truncate max-w-[180px]">
                                            {{ $anexo->nome_original }}
                                        </span>
                                        <span class="text-[11px] text-gray-500 dark:text-gray-400">
                                            {{ $anexo->created_at?->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($anexo->is_conclusao)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-sky-100 text-sky-700 border border-sky-200">
                                            Conclusao
                                        </span>
                                    @endif
                                    <a href="{{ $url }}"
                                    target="_blank"
                                    class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 text-[11px] font-semibold">
                                        Abrir
                                    </a>
                                    <a href="{{ $url }}"
                                    download
                                    class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white text-[11px]">
                                        Baixar
                                    </a>
                                </div>
                            </div>

                            @if ($isImage)
                                <div class="mt-1">
                                    <a href="{{ $url }}" target="_blank">
                                        <img
                                            src="{{ $url }}"
                                            alt="Anexo {{ $anexo->nome_original }}"
                                            class="max-h-56 rounded-md border border-gray-100 dark:border-gray-700 object-contain bg-gray-50 dark:bg-gray-900"
                                        >
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Nenhum anexo enviado para esta OS.
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>

            {{-- Rodape ajustes --}}
            <div
                class="flex flex-wrap items-center justify-end gap-2 pt-1"
                x-data="{ concluirModal: @json($errors->has('observacao_conclusao') || $errors->has('anexo_conclusao')) }"
            >
                {{-- Voltar --}}
                @php
                    $user = auth()->user();
                    $voltarUrl = route('ordens.index');

                    if ($user && $user->hasRole('visualizador')) {
                        $voltarUrl = route('dashboard.visualizador');
                    } elseif ($user && $user->hasRole('tecnico')) {
                        $voltarUrl = route('tecnico.dashboard');
                    }
                @endphp
                <a href="{{ $voltarUrl }}"
                   class="inline-flex items-center h-9 px-4 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md
                          text-[11px] font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest leading-none
                          hover:bg-gray-200 dark:hover:bg-gray-700">
                    Voltar para lista
                </a>

                {{-- Acoes do tecnico --}}
                @if($ehTecnicoDaOrdem)
                    <div class="flex items-center gap-2">
                        @if($ordem->status === 'aberta')
                            <form action="{{ route('ordens.executar', $ordem) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center h-9 px-4 bg-amber-500 hover:bg-amber-600 border border-amber-600 rounded-md
                                        text-[11px] font-semibold text-white uppercase tracking-widest leading-none shadow-sm">
                                    Executar ordem
                                </button>
                            </form>
                        @elseif($ordem->status === 'em_execucao')
                            <button type="button"
                                @click="concluirModal = true"
                                class="inline-flex items-center h-9 px-4 bg-verdes-verde_claro hover:bg-verdes-verde_folha border0 rounded-md
                                    text-[11px] font-semibold text-white uppercase tracking-widest leading-none shadow-sm">
                                Concluir ordem
                            </button>
                        @endif
                    </div>

                    {{-- Modal Concluir --}}
                    <div
                        x-cloak
                        x-show="concluirModal"
                        x-transition.opacity
                        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm"
                    ></div>
                    <div
                        x-cloak
                        x-show="concluirModal"
                        x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center px-4"
                    >
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg border border-gray-200 dark:border-gray-700">
                            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">Conclusao da OS</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 font-semibold mt-0.5">{{ $ordem->codigo ?? $ordem->id }}</p>
                                </div>
                                <button type="button" class="text-gray-400 hover:text-gray-600" @click="concluirModal = false">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('ordens.concluir', $ordem) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="px-5 py-4 space-y-4">
                                    <div>
                                        <label for="observacao_conclusao" class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                                            Observacao (opcional)
                                        </label>
                                        <textarea
                                            id="observacao_conclusao"
                                            name="observacao_conclusao"
                                            rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                            placeholder="Descreva o que foi feito ou observado ao concluir"
                                        >{{ old('observacao_conclusao') }}</textarea>
                                        @error('observacao_conclusao')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="anexo_conclusao" class="text-[11px] uppercase text-gray-500 dark:text-gray-400 font-semibold">
                                            Foto da execucao (opcional)
                                        </label>
                                        <input
                                            type="file"
                                            name="anexo_conclusao"
                                            id="anexo_conclusao"
                                            accept=".jpg,.jpeg,.png"
                                            class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:uppercase file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200"
                                        >
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Formatos JPG ou PNG, ate 2MB.</p>
                                        @error('anexo_conclusao')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900/60 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
                                    <button type="button"
                                        @click="concluirModal = false"
                                        class="inline-flex items-center h-9 px-4 rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-[11px] font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest leading-none">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center h-9 px-4 rounded-md bg-verdes-verde_claro hover:bg-verdes-verde_folha text-white text-[11px] font-semibold uppercase tracking-widest leading-none shadow-sm">
                                        Concluir OS
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

