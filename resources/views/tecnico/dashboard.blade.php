<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                    dark:border-white/10 dark:bg-gray-900">

            {{-- barra de acento (marca) --}}
            <div class="h-1.5 w-full bg-verdes-verde_claro"></div>

            <div class="flex items-center justify-between gap-4 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        {{ __('Painel do Tecnico') }}
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-300">
                        Visão rápida das suas ordens e prioridades.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Cards de resumo --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                {{-- Card 1 --}}
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                            hover:shadow-md transition
                            dark:border-white/10 dark:bg-gray-900">
                    <div class="h-1.5 w-full bg-verdes-verde_claro"></div>

                    <div class="p-6 transition group-hover:-translate-y-[1px]">
                        <div class="flex items-start justify-between gap-4">
                            <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                OS Abertas
                            </p>
                            <img src="{{ asset('imagem/engrenagem_alerta.png') }}"
                                 alt="Engrenagem alerta"
                                 class="h-9 w-9 object-contain opacity-90 dark:hidden">
                            <img src="{{ asset('imagem/engrenagem_alerta_white.png') }}"
                                 alt="Engrenagem alerta branca"
                                 class="hidden h-9 w-9 object-contain opacity-90 dark:block">
                        </div>
                        <p class="mt-2 text-3xl font-extrabold text-verdes-verde_claro">
                            {{ $osAbertas }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Aguardando inicio da execucao.
                        </p>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                            hover:shadow-md transition
                            dark:border-white/10 dark:bg-gray-900">
                    <div class="h-1.5 w-full bg-verdes-verde_claro"></div>

                    <div class="p-6 transition group-hover:-translate-y-[1px]">
                        <div class="flex items-start justify-between gap-4">
                            <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Em execucao
                            </p>
                            <img src="{{ asset('imagem/engrenagem_play.png') }}"
                                 alt="Engrenagem em execucao"
                                 class="h-9 w-9 object-contain opacity-90 dark:hidden">
                            <img src="{{ asset('imagem/engrenagem_play_white.png') }}"
                                 alt="Engrenagem em execucao branca"
                                 class="hidden h-9 w-9 object-contain opacity-90 dark:block">
                        </div>
                        <p class="mt-2 text-3xl font-extrabold text-verdes-verde_claro">
                            {{ $osExecucao }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            OS em que voce esta trabalhando.
                        </p>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                            hover:shadow-md transition
                            dark:border-white/10 dark:bg-gray-900">
                    <div class="h-1.5 w-full bg-verdes-verde_claro"></div>

                    <div class="p-6 transition group-hover:-translate-y-[1px]">
                        <div class="flex items-start justify-between gap-4">
                            <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Concluidas
                            </p>
                            <img src="{{ asset('imagem/engrenagem.png') }}"
                                 alt="Engrenagem concluida"
                                 class="h-9 w-9 object-contain opacity-90 dark:hidden">
                            <img src="{{ asset('imagem/engrenagem_white.png') }}"
                                 alt="Engrenagem concluida branca"
                                 class="hidden h-9 w-9 object-contain opacity-90 dark:block">
                        </div>
                        <p class="mt-2 text-3xl font-extrabold text-verdes-verde_claro">
                            {{ $osConcluidas }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            OS finalizadas atribuidas a voce.
                        </p>
                    </div>
                </div>

            </div>

            {{-- Lista de OS atribuidas ao tecnico --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                        dark:border-white/10 dark:bg-gray-900">

                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>

                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                            Ordens atribuidas
                        </h3>
                        <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-300">
                            Acompanhe suas OS e priorize pelo status e urgência.
                        </p>
                    </div>

                    <form method="GET" class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox"
                                   name="mostrar_concluidas"
                                   value="1"
                                   @checked($mostrarConcluidas)
                                   onchange="this.form.submit()"
                                   class="h-4 w-4 rounded border-gray-300 text-verdes-verde_claro shadow-sm focus:ring-verdes-verde_claro/40">
                            <span>Mostrar concluidas</span>
                        </label>
                    </form>
                </div>

                <div class="px-6 pb-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                        <thead class="sticky top-0 z-10 bg-white dark:bg-gray-900">
                            <tr>
                                <th class="pl-3 pr-3 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Codigo
                                </th>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Criada em
                                </th>
                                <th class="px-3 py-3 text-right text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            @forelse($ordens as $os)
                                @php
                                    $status = strtolower($os->status ?? '');
                                    $statusLabel = match ($status) {
                                        'aberta'       => 'ABERTA',
                                        'em_execucao'  => 'EM EXECUCAO',
                                        'concluida'    => 'CONCLUIDA',
                                        'cancelada'    => 'CANCELADA',
                                        default        => strtoupper(str_replace('_', ' ', $os->status ?? '-')),
                                    };
                                    $statusIcon = match ($status) {
                                        'aberta'      => 'imagem/engrenagem_alerta.png',
                                        'em_execucao' => 'imagem/engrenagem_play.png',
                                        'concluida'   => 'imagem/engrenagem.png',
                                        'cancelada'   => 'imagem/engrenagem_alerta.png',
                                        default       => 'imagem/engrenagem_alerta.png',
                                    };

                                    $statusIconDark = match ($status) {
                                        'aberta'      => 'imagem/engrenagem_alerta_white.png',
                                        'em_execucao' => 'imagem/engrenagem_play_white.png',
                                        'concluida'   => 'imagem/engrenagem_white.png',
                                        'cancelada'   => 'imagem/engrenagem_alerta_white.png',
                                        default       => 'imagem/engrenagem_alerta_white.png',
                                    };

                                    $prioridadeRaw = $os->prioridade ?? null;
                                    $prioridade = $prioridadeRaw ? strtolower(trim($prioridadeRaw)) : null;

                                    // faixa de prioridade (alto em vermelho)
                                    $priorityBarClass = match (true) {
                                        $prioridade === 'alto'
                                            || $prioridade === 'muito_alto' => 'bg-red-500',
                                        $prioridade === 'medio'            => 'bg-yellow-400',
                                        $prioridade === 'baixo'            => 'bg-verdes-verde_claro',
                                        default                            => 'bg-verdes-verde_claro/40',
                                    };

                                    $setorNome = $os->setor->nome
                                        ?? $os->equipamento->setor->nome
                                        ?? '-';
                                @endphp

                                <tr class="hover:bg-gray-50/70 dark:hover:bg-white/5 transition">
                                    <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-11 w-11 items-center justify-center rounded-md {{ $priorityBarClass }}"
                                                  title="Status {{ $statusLabel }}">
                                                <img src="{{ asset($statusIcon) }}"
                                                     alt="Status {{ $statusLabel }}"
                                                     class="h-8 w-8 object-contain opacity-90 dark:hidden">
                                                <img src="{{ asset($statusIconDark) }}"
                                                     alt="Status {{ $statusLabel }}"
                                                     class="hidden h-8 w-8 object-contain opacity-90 dark:block">
                                            </span>
                                            <div class="min-w-0 leading-tight">
                                                <div class="font-semibold">#{{ $os->codigo ?? $os->id }}</div>
                                                <div class="mt-0.5 max-w-[260px] text-xs font-normal text-gray-600 dark:text-gray-300 truncate"
                                                     title="{{ $setorNome }} - {{ $os->equipamento->nome ?? '-' }}">
                                                    {{ $setorNome }} - {{ $os->equipamento->nome ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->created_at?->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-3 py-3 text-sm text-right">
                                        <a href="{{ route('ordens.show', $os) }}"
                                           class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold
                                                  bg-verdes-verde_claro/10 text-verdes-verde_claro hover:bg-verdes-verde_claro/15
                                                  dark:bg-verdes-verde_claro/15 dark:text-verdes-verde_claro dark:hover:bg-verdes-verde_claro/25 transition">
                                            Visualizar O.S
                                            <span class="text-verdes-verde_claro">→</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhuma OS atribuida a voce ate o momento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $ordens->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
