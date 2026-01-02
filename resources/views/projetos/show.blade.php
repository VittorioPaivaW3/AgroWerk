<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Projeto') }} - {{ $projeto->titulo }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="px-6 py-5 space-y-3 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $projeto->titulo }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Setor: {{ $projeto->setor->nome ?? '—' }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border
                            @class([
                                'bg-emerald-50 text-emerald-700 border-emerald-200' => $projeto->status === 'aberto',
                                'bg-amber-50 text-amber-700 border-amber-200'       => $projeto->status === 'em_andamento',
                                'bg-sky-50 text-sky-700 border-sky-200'            => $projeto->status === 'concluido',
                                'bg-red-50 text-red-700 border-red-200'            => $projeto->status === 'cancelado',
                            ])">
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
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
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
                                        <a href="{{ $url }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 text-xs font-semibold">Abrir</a>
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
