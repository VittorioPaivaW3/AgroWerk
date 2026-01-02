<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes do Equipamento') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $equipamento->nome }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Codigo: {{ $equipamento->codigo ?? $equipamento->id }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('equipamentos.edit', $equipamento) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                              text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                              hover:bg-gray-200 dark:hover:bg-gray-600">
                        Editar
                    </a>

                    <a href="{{ route('equipamentos.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                              text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                              hover:bg-gray-50 dark:hover:bg-gray-700">
                        Voltar
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 text-gray-900 dark:text-gray-100 space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Setor --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                Setor
                            </p>
                            <p class="text-sm">
                                {{ $equipamento->setor->nome ?? '-' }}
                            </p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                Status
                            </p>

                            @php
                                $statusRaw = $equipamento->status ?? null;
                                $status = $statusRaw ? strtolower(trim($statusRaw)) : null;
                                $statusLabel = match ($status) {
                                    'ativo'      => 'Ativo',
                                    'inativo'    => 'Inativo',
                                    'manutencao' => 'Em manutencao',
                                    default      => ($statusRaw ?: '-'),
                                };
                                $badgeStyle = match ($status) {
                                    'ativo'      => 'background-color:#dcfce7;color:#166534;',
                                    'inativo'    => 'background-color:#fee2e2;color:#991b1b;',
                                    'manutencao' => 'background-color:#fef9c3;color:#854d0e;',
                                    default      => 'background-color:#e5e7eb;color:#111827;',
                                };
                            @endphp

                            <p class="mt-0.5">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    style="{{ $badgeStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Cor --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                            Cor
                        </p>
                        <p class="mt-0.5 flex items-center gap-2 text-sm">
                            @if($equipamento->cor)
                                <span class="inline-block h-4 w-4 rounded-full border border-gray-300"
                                      style="background-color: {{ $equipamento->cor }}"></span>
                                <span>{{ $equipamento->cor }}</span>
                            @else
                                -
                            @endif
                        </p>
                    </div>

                    {{-- Manutencao preventiva --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                            Manutencao preventiva
                        </p>
                        <p class="mt-0.5 text-sm">
                            {{ $equipamento->manutencao_preventiva
                                ? \Illuminate\Support\Carbon::parse($equipamento->manutencao_preventiva)->format('d/m/Y')
                                : '-' }}
                        </p>
                    </div>

                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        Pertence a: 
                        <span class="font-semibold">
                            {{ $equipamento->terceiro ? 'Terceiros' : 'Próprio' }}
                        </span>
                    </p>

                    {{-- Observacoes --}}
                    @if($equipamento->observacoes)
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Observacoes
                            </h4>
                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">
                                {{ $equipamento->observacoes }}
                            </p>
                        </div>
                    @endif

                    {{-- Arquivos anexados --}}
                    @if($equipamento->arquivos && $equipamento->arquivos->count())
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Arquivos anexados
                            </h4>

                            <ul class="space-y-2">
                                @foreach($equipamento->arquivos as $arquivo)
                                    <li class="flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-2">
                                            @php
                                                $isPdf = str_contains($arquivo->mime_type ?? '', 'pdf');
                                            @endphp

                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-md
                                                         bg-gray-100 dark:bg-gray-700 text-xs font-semibold">
                                                {{ $isPdf ? 'PDF' : 'IMG' }}
                                            </span>

                                            <a href="{{ route('equipamentos.arquivos.show', $arquivo) }}"
                                               target="_blank"
                                               class="text-indigo-600 dark:text-indigo-400 hover:underline">
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
                    @endif

                    {{-- Campos adicionais --}}
                    @php
                        $camposExtrasRaw = $equipamento->campos_extras ?? [];

                        $camposExtras = collect($camposExtrasRaw)->map(function ($item, $key) {
                            // Formato lista [{campo/label/key, valor/value}] -> preserva duplicados
                            if (is_array($item) && (isset($item['campo']) || isset($item['label']) || isset($item['key']))) {
                                return [
                                    'label' => $item['campo'] ?? $item['label'] ?? $item['key'] ?? '',
                                    'valor' => $item['valor'] ?? $item['value'] ?? '',
                                ];
                            }

                            // Formato associativo legado ['n_serie' => '123']
                            if (! is_array($item)) {
                                return [
                                    'label' => $key,
                                    'valor' => $item,
                                ];
                            }

                            return null;
                        })->filter(function ($item) {
                            return $item
                                && ($item['label'] !== '' || ($item['valor'] !== null && $item['valor'] !== ''));
                        });
                    @endphp
                    @if($camposExtras->isNotEmpty())
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">
                                Componentes
                            </p>

                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                                @foreach($camposExtras as $extra)
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                            {{ ucfirst(str_replace('_', ' ', $extra['label'])) }}
                                        </dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $extra['valor'] }}
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
