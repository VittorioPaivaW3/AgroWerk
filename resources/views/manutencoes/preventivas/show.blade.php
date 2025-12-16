<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes da Manutenção Preventiva') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Topo: título + botões --}}
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $manutencao->equipamento->nome ?? 'Equipamento não informado' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($manutencao->equipamento && $manutencao->equipamento->setor)
                            Setor: {{ $manutencao->equipamento->setor->nome }}
                        @else
                            Setor: —
                        @endif
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('manutencoes.preventivas.edit', $manutencao) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                              text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                              hover:bg-gray-200 dark:hover:bg-gray-600">
                        Editar
                    </a>

                    <a href="{{ route('manutencoes.preventivas.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                              text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                              hover:bg-gray-50 dark:hover:bg-gray-700">
                        Voltar
                    </a>
                </div>
            </div>

            {{-- Cartão principal --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 text-gray-900 dark:text-gray-100 space-y-4">

                    @php
                        $statusRaw = $manutencao->status ?? null;
                        $status = $statusRaw ? strtolower(trim($statusRaw)) : null;

                        $statusLabel = match ($status) {
                            'pendente'  => 'Pendente',
                            'concluida' => 'Concluída',
                            default     => ($statusRaw ?: '—'),
                        };

                        $badgeStyle = match ($status) {
                            'pendente'  => 'background-color:#fef9c3;color:#854d0e;',   // amarelo
                            'concluida' => 'background-color:#dcfce7;color:#166534;',   // verde
                            default     => 'background-color:#e5e7eb;color:#111827;',   // cinza
                        };
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Data prevista --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                Data prevista
                            </p>
                            <p class="mt-0.5 text-sm">
                                {{ $manutencao->data_prevista
                                    ? \Illuminate\Support\Carbon::parse($manutencao->data_prevista)->format('d/m/Y')
                                    : '—' }}
                            </p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                                Status
                            </p>
                            <p class="mt-0.5 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="{{ $badgeStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Descrição --}}
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            O que deve ser feito
                        </h4>
                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">
                            {{ $manutencao->descricao }}
                        </p>
                    </div>

                    {{-- Ações extras: concluir / excluir --}}
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Criada em {{ $manutencao->created_at->format('d/m/Y H:i') }}
                        </div>

                        <div class="flex gap-3">
                            @if($status !== 'concluida')
                                <form method="POST"
                                      action="{{ route('manutencoes.preventivas.concluir', $manutencao) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-xs font-semibold"
                                            onclick="return confirm('Marcar esta manutenção como concluída?')">
                                        Marcar como concluída
                                    </button>
                                </form>
                            @endif

                            <form method="POST"
                                  action="{{ route('manutencoes.preventivas.destroy', $manutencao) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-semibold"
                                        onclick="return confirm('Tem certeza que deseja excluir esta manutenção?')">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
