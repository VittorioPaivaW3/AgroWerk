<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl border border-gray-200/70 bg-white/70 shadow-sm backdrop-blur
                    dark:border-white/10 dark:bg-gray-900/60">

            {{-- barra de acento (marca) --}}
            <div class="h-1 w-full"
                 style="background-image: linear-gradient(90deg, #023D1D 0%, #009640 45%, #94C11F 100%);">
            </div>

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
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200/70 bg-white/70 shadow-sm backdrop-blur
                            hover:shadow-md transition
                            dark:border-white/10 dark:bg-gray-900/60">
                    <div class="h-1 w-full"
                         style="background-image: linear-gradient(90deg, #023D1D 0%, #009640 45%, #94C11F 100%);">
                    </div>

                    <div class="p-5 transition group-hover:-translate-y-[1px]">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            OS Abertas
                        </p>
                        <p class="mt-2 text-3xl font-extrabold text-[#009640]">
                            {{ $osAbertas }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Aguardando inicio da execucao.
                        </p>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200/70 bg-white/70 shadow-sm backdrop-blur
                            hover:shadow-md transition
                            dark:border-white/10 dark:bg-gray-900/60">
                    <div class="h-1 w-full"
                         style="background-image: linear-gradient(90deg, #023D1D 0%, #009640 45%, #94C11F 100%);">
                    </div>

                    <div class="p-5 transition group-hover:-translate-y-[1px]">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            Em execucao
                        </p>
                        <p class="mt-2 text-3xl font-extrabold text-amber-500">
                            {{ $osExecucao }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            OS em que voce esta trabalhando.
                        </p>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200/70 bg-white/70 shadow-sm backdrop-blur
                            hover:shadow-md transition
                            dark:border-white/10 dark:bg-gray-900/60">
                    <div class="h-1 w-full"
                         style="background-image: linear-gradient(90deg, #023D1D 0%, #009640 45%, #94C11F 100%);">
                    </div>

                    <div class="p-5 transition group-hover:-translate-y-[1px]">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            Concluidas
                        </p>
                        <p class="mt-2 text-3xl font-extrabold text-sky-500">
                            {{ $osConcluidas }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            OS finalizadas atribuidas a voce.
                        </p>
                    </div>
                </div>

            </div>

            {{-- Lista de OS atribuidas ao tecnico --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200/70 bg-white/70 shadow-sm backdrop-blur
                        dark:border-white/10 dark:bg-gray-900/60">

                <div class="h-1 w-full"
                     style="background-image: linear-gradient(90deg, #023D1D 0%, #009640 45%, #94C11F 100%);">
                </div>

                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                            Ordens atribuidas
                        </h3>
                        <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-300">
                            Acompanhe suas OS e priorize pelo status e urgência.
                        </p>
                    </div>

                    <form method="GET" class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox"
                                   name="mostrar_concluidas"
                                   value="1"
                                   @checked($mostrarConcluidas)
                                   onchange="this.form.submit()"
                                   class="rounded border-gray-300 text-[#009640] shadow-sm focus:ring-[#94C11F]">
                            <span>Mostrar concluidas</span>
                        </label>
                    </form>
                </div>

                <div class="px-6 pb-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                        <thead class="sticky top-0 z-10 bg-white/80 backdrop-blur dark:bg-gray-900/70">
                            <tr>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Codigo
                                </th>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Setor
                                </th>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Equipamento
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

                                    $statusClasses = match ($status) {
                                        'aberta'       => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20 dark:bg-emerald-400/10 dark:text-emerald-200 dark:border-emerald-300/20',
                                        'em_execucao'  => 'bg-amber-500/10 text-amber-700 border-amber-500/20 dark:bg-amber-400/10 dark:text-amber-200 dark:border-amber-300/20',
                                        'concluida'    => 'bg-sky-500/10 text-sky-700 border-sky-500/20 dark:bg-sky-400/10 dark:text-sky-200 dark:border-sky-300/20',
                                        'cancelada'    => 'bg-red-500/10 text-red-700 border-red-500/20 dark:bg-red-400/10 dark:text-red-200 dark:border-red-300/20',
                                        default        => 'bg-gray-500/10 text-gray-700 border-gray-500/20 dark:bg-white/10 dark:text-gray-200 dark:border-white/10',
                                    };

                                    $prioridadeRaw = $os->prioridade ?? null;
                                    $prioridade = $prioridadeRaw ? strtolower(trim($prioridadeRaw)) : null;

                                    // borda da linha: concluida verde; caso contrario, segue prioridade
                                    $priorityBorderClass = match (true) {
                                        $status === 'concluida'      => 'border-l-4 border-[#009640]',
                                        $prioridade === 'alto',
                                        $prioridade === 'muito_alto' => 'border-l-4 border-red-500',
                                        $prioridade === 'medio'      => 'border-l-4 border-amber-400',
                                        $prioridade === 'baixo'      => 'border-l-4 border-sky-400',
                                        default                      => 'border-l-4 border-transparent',
                                    };

                                    $dotClass = match ($status) {
                                        'aberta'      => 'bg-emerald-500',
                                        'em_execucao' => 'bg-amber-500',
                                        'concluida'   => 'bg-sky-500',
                                        'cancelada'   => 'bg-red-500',
                                        default       => 'bg-gray-400',
                                    };

                                    $setorNome = $os->setor->nome
                                        ?? $os->equipamento->setor->nome
                                        ?? '-';
                                @endphp

                                <tr class="hover:bg-gray-50/70 dark:hover:bg-white/5 transition">
                                    <td class="px-3 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 {{ $priorityBorderClass }}">
                                        #{{ $os->codigo ?? $os->id }}
                                    </td>

                                    <td class="px-3 py-3 text-sm">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[11px] font-extrabold tracking-wide uppercase border {{ $statusClasses }}">
                                            <span class="h-2.5 w-2.5 rounded-full {{ $dotClass }}"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $setorNome }}
                                    </td>

                                    <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->equipamento->nome ?? '-' }}
                                    </td>

                                    <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->created_at?->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-3 py-3 text-sm text-right">
                                        <a href="{{ route('ordens.show', $os) }}"
                                           class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold
                                                  bg-[#009640]/10 text-[#023D1D] hover:bg-[#009640]/15
                                                  dark:bg-[#009640]/15 dark:text-emerald-200 dark:hover:bg-[#009640]/25 transition">
                                            Visualizar O.S
                                            <span class="text-[#009640]">→</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
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
