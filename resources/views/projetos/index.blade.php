<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Projetos') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Cadastro e acompanhamento de projetos
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Visualize e gerencie os projetos cadastrados.
                        </p>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('projetos.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-verdes-verde_folha
                                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Novo projeto
                        </a>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-verdes-verde_claro/10 dark:bg-verdes-verde_claro/10">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Projeto</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setor</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prazo</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($projetos as $proj)
                                @php
                                    $status = $proj->status ?? '';
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
                                <tr class="hover:bg-verdes-verde_claro/5 dark:hover:bg-verdes-verde_claro/10">
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $proj->titulo }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $proj->setor->nome ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $proj->prazo?->format('d/m/Y') ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-semibold border
                                            @class([
                                                'bg-verdes-verde_claro/20 text-verdes-verde_escuro border-verdes-verde_claro/40' => $proj->status === 'aberto',
                                                'bg-amber-50 text-amber-700 border-amber-200'       => $proj->status === 'em_andamento',
                                                'bg-verdes-verde_folha text-white border-verdes-verde_folha'            => $proj->status === 'concluido',
                                                'bg-red-50 text-red-700 border-red-200'            => $proj->status === 'cancelado',
                                            ])">
                                            <img src="{{ asset($statusIcon) }}"
                                                 alt="Status {{ Str::ucfirst(str_replace('_', ' ', $proj->status)) }}"
                                                 class="h-3.5 w-3.5 object-contain dark:hidden">
                                            <img src="{{ asset($statusIconDark) }}"
                                                 alt="Status {{ Str::ucfirst(str_replace('_', ' ', $proj->status)) }}"
                                                 class="hidden h-3.5 w-3.5 object-contain dark:block">
                                            {{ Str::ucfirst(str_replace('_', ' ', $proj->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right">
                                        <a href="{{ route('projetos.show', $proj) }}"
                                           class="text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-xs font-semibold">
                                            Ver
                                        </a>
                                        <span class="mx-1 text-gray-400">|</span>
                                        <a href="{{ route('projetos.edit', $proj) }}"
                                           class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white text-xs font-semibold">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhum projeto cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $projetos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
