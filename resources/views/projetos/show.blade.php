<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Projeto') }} - {{ $projeto->titulo }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 space-y-3 text-gray-900 dark:text-gray-100">
                    @php
                        $status = $projeto->status ?? '';
                        $statusIcon = match ($status) {
                            'aberto'       => 'imagem/engrenagem_alerta.png',
                            'em_andamento' => 'imagem/engrenagem_play.png',
                            'concluido'    => 'imagem/engrenagem.png',
                            'cancelado'    => 'imagem/engrenagem_alerta.png',
                            default        => 'imagem/engrenagem_alerta.png',
                        };

                        $statusIconDark = match ($status) {
                            'aberto'       => 'imagem/engrenagem_alerta_white.png',
                            'em_andamento' => 'imagem/engrenagem_play_white.png',
                            'concluido'    => 'imagem/engrenagem_white.png',
                            'cancelado'    => 'imagem/engrenagem_alerta_white.png',
                            default        => 'imagem/engrenagem_alerta_white.png',
                        };
                    @endphp
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $projeto->titulo }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Setor: {{ $projeto->setor->nome ?? '—' }}
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border
                            @class([
                                'bg-verdes-verde_claro/20 text-verdes-verde_escuro border-verdes-verde_claro/40' => $projeto->status === 'aberto',
                                'bg-amber-50 text-amber-700 border-amber-200'       => $projeto->status === 'em_andamento',
                                'bg-verdes-verde_folha text-white border-verdes-verde_folha'            => $projeto->status === 'concluido',
                                'bg-red-50 text-red-700 border-red-200'            => $projeto->status === 'cancelado',
                            ])">
                            <img src="{{ asset($statusIcon) }}"
                                 alt="Status {{ Str::ucfirst(str_replace('_', ' ', $projeto->status)) }}"
                                 class="h-3.5 w-3.5 object-contain dark:hidden">
                            <img src="{{ asset($statusIconDark) }}"
                                 alt="Status {{ Str::ucfirst(str_replace('_', ' ', $projeto->status)) }}"
                                 class="hidden h-3.5 w-3.5 object-contain dark:block">
                            {{ Str::ucfirst(str_replace('_', ' ', $projeto->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Prazo</p>
                            <p>{{ $projeto->prazo?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Orçamento previsto</p>
                            <p>{{ $projeto->orcamento_previsto !== null ? 'R$ '.number_format($projeto->orcamento_previsto, 2, ',', '.') : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Orçamento real</p>
                            <p>{{ $projeto->orcamento_real !== null ? 'R$ '.number_format($projeto->orcamento_real, 2, ',', '.') : '—' }}</p>
                        </div>
                    </div>

                    @if($projeto->descricao)
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold mb-1">Descrição</p>
                            <p class="text-sm whitespace-pre-line">{{ $projeto->descricao }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Anexos --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Anexos</h4>
                        @if($projeto->orcamentos->count())
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $projeto->orcamentos->count() }} arquivo(s)</span>
                        @endif
                    </div>

                    @if($projeto->orcamentos->count())
                        <ul class="space-y-2 text-sm">
                            @foreach($projeto->orcamentos as $arquivo)
                                @php
                                    $url = $arquivo->path ? asset('storage/'.$arquivo->path) : '#';
                                @endphp
                                <li class="flex items-center justify-between">
                                    <span class="truncate max-w-xs">{{ $arquivo->nome_original ?? basename($arquivo->path) }}</span>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ $url }}" target="_blank" class="text-verdes-verde_claro dark:text-verdes-verde_claro text-xs font-semibold">Abrir</a>
                                        <a href="{{ $url }}" download class="text-gray-600 dark:text-gray-300 text-xs">Baixar</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum anexo enviado.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
