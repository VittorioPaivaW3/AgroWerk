<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Equipamentos') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Barra superior: título + botão --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Lista de equipamentos
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Visualize e filtre os equipamentos cadastrados no sistema.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('equipamentos.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest
                                  hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Cadastrar Equipamento
                        </a>
                    </div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1 w-full bg-verdes-verde_claro/30"></div>
                <div class="px-6 py-4">
                    <form method="GET" action="{{ route('equipamentos.index') }}"
                          class="grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">
                        {{-- Filtro por setor --}}
                        <div>
                            <label for="setor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Setor
                            </label>
                            <select id="setor_id" name="setor_id"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                <option value="">Todos</option>
                                @foreach ($setores as $setor)
                                    <option value="{{ $setor->id }}" @selected(request('setor_id') == $setor->id)>
                                        {{ $setor->nome}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Busca por nome --}}
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Buscar por nome
                            </label>
                            <input id="search" name="search" type="text"
                                   value="{{ request('search') }}"
                                   placeholder="Digite o nome do equipamento..."
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                        </div>

                        {{-- Botões --}}
                        <div class="flex gap-2 md:justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                           font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                Filtrar
                            </button>

                            <a href="{{ route('equipamentos.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                                      font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-200 dark:hover:bg-gray-600">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabela de equipamentos --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Setor
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tipo
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($equipamentos as $equipamento)
                                @php
                                    $statusRaw = $equipamento->status ?? null;
                                    $status = $statusRaw ? strtolower(trim($statusRaw)) : null;

                                    $statusLabel = match ($status) {
                                        'ativo'      => 'Ativo',
                                        'inativo'    => 'Inativo',
                                        'manutencao' => 'Em manutenção',
                                        default      => ($statusRaw ?: '—'),
                                    };

                                    $badgeClass = match ($status) {
                                        'ativo'      => 'bg-verdes-verde_claro/20 text-verdes-verde_claro',
                                        'inativo'    => 'bg-red-100 text-red-700',
                                        'manutencao' => 'bg-yellow-100 text-yellow-800',
                                        default      => 'bg-gray-200 text-gray-900',
                                    };
                                @endphp

                                <tr>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $equipamento->codigo ?? $equipamento->id }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="flex items-center gap-2">
                                            @if($equipamento->cor)
                                                <span class="inline-block h-3 w-3 rounded-full border border-gray-300"
                                                      style="background-color: {{ $equipamento->cor }}"></span>
                                            @endif
                                            <span>{{ $equipamento->nome }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $equipamento->setor->nome ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $equipamento->tipoEquipamento->nome ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right space-x-2">
                                        <a href="{{ route('equipamentos.show', $equipamento) }}"
                                           class="text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-xs font-semibold">
                                            Ver
                                        </a>
                                        @role('admin|gestor')
                                            <a href="{{ route('equipamentos.edit', $equipamento) }}"
                                               class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white text-xs font-semibold">
                                                Editar
                                            </a>
                                        @endrole
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhum equipamento encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Paginação --}}
                    <div class="mt-4">
                        {{ $equipamentos->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
