<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manutenções Preventivas') }}
        </h2>
    </x-slot>

    {{-- x-data único para create + view --}}
    <div class="py-8"
         x-data="{
            openCreate: false,
            openView: false,
            view: {
                equipamento: '',
                setor: '',
                data_prevista: '',
                descricao: '',
                status_label: '',
                status_badge_class: ''
            }
         }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Topo: título + botão --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Agenda de manutenções preventivas
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Visualize as manutenções preventivas vinculadas aos equipamentos.
                        </p>
                    </div>

                    @role('admin|gestor')
                        <div class="flex justify-end">
                            <button
                                type="button"
                                @click="openCreate = true"
                                class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                       font-semibold text-xs text-white uppercase tracking-widest
                                       hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro"
                            >
                                Incluir Manutenção Preventiva
                            </button>
                        </div>
                    @endrole
                </div>
            </div>

            {{-- Alertas --}}
            @if (session('success'))
                <div class="bg-verdes-verde_claro/15 border border-verdes-verde_claro/30 text-verdes-verde_escuro text-sm px-4 py-2 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- CALENDÁRIO --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-4 py-4 sm:px-6 sm:py-6">
                    <div id="calendar"></div>
                </div>
            </div>

            {{-- FILTROS DA LISTAGEM --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1 w-full bg-verdes-verde_claro/30"></div>
                <div class="px-6 py-4">
                    <form method="GET"
                          action="{{ route('manutencoes.preventivas.index') }}"
                          class="grid grid-cols-1 gap-4 md:grid-cols-4 md:items-end">

                        {{-- Filtro por equipamento --}}
                        <div>
                            <label for="equipamento_id"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Equipamento
                            </label>
                            <select id="equipamento_id" name="equipamento_id"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                <option value="">Todos</option>
                                @foreach($equipamentos as $equipamento)
                                    <option value="{{ $equipamento->id }}"
                                        @selected(request('equipamento_id') == $equipamento->id)>
                                        {{ $equipamento->nome }}
                                        @if($equipamento->setor) - {{ $equipamento->setor->nome }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por setor --}}
                        <div>
                            <label for="setor_id"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Setor
                            </label>
                            <select id="setor_id" name="setor_id"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                <option value="">Todos</option>
                                @foreach($setores as $setor)
                                    <option value="{{ $setor->id }}"
                                        @selected(request('setor_id') == $setor->id)>
                                        {{ $setor->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por data --}}
                        <div>
                            <label for="data"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data prevista
                            </label>
                            <input type="date"
                                   id="data"
                                   name="data"
                                   value="{{ request('data') }}"
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

                            <a href="{{ route('manutencoes.preventivas.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                                      font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-200 dark:hover:bg-gray-600">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabela de manutenções --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-verdes-verde_claro/10 dark:bg-verdes-verde_claro/10">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Equipamento
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Setor
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Data prevista
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Descrição
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
                            @forelse ($manutencoes as $manutencao)
                                @php
                                    $statusRaw = $manutencao->status ?? null;
                                    $status = $statusRaw ? strtolower(trim($statusRaw)) : null;

                                    $statusLabel = match ($status) {
                                        'pendente'  => 'Pendente',
                                        'concluida' => 'Concluída',
                                        default     => ($statusRaw ?: '—'),
                                    };

                                    $badgeClass = match ($status) {
                                        'pendente'  => 'bg-verdes-verde_claro/15 text-verdes-verde_folha',
                                        'concluida' => 'bg-verdes-verde_folha text-white',
                                        default     => 'bg-gray-200 text-gray-900',
                                    };
                                @endphp

                                <tr class="hover:bg-verdes-verde_claro/5 dark:hover:bg-verdes-verde_claro/10 transition">
                                    {{-- Equipamento --}}
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        @if($manutencao->equipamento)
                                            <a href="{{ route('equipamentos.show', $manutencao->equipamento) }}"
                                               class="text-verdes-verde_claro dark:text-verdes-verde_claro hover:underline">
                                                {{ $manutencao->equipamento->nome }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    {{-- Setor --}}
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $manutencao->equipamento->setor->nome ?? '—' }}
                                    </td>

                                    {{-- Data prevista --}}
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $manutencao->data_prevista
                                            ? $manutencao->data_prevista->format('d/m/Y')
                                            : '—' }}
                                    </td>

                                    {{-- Descrição resumida --}}
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        <span class="block max-w-xs truncate" title="{{ $manutencao->descricao }}">
                                            {{ $manutencao->descricao }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-3 py-2 text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    {{-- Ações --}}
                                    <td class="px-3 py-2 text-sm text-right">
                                        <div class="inline-flex items-center gap-2">
                                            {{-- VER - abre modal --}}
                                            <button type="button"
                                                class="text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-xs font-semibold"
                                                @click="
                                                    view.equipamento = '{{ $manutencao->equipamento->nome ?? '—' }}';
                                                    view.setor = '{{ $manutencao->equipamento->setor->nome ?? '—' }}';
                                                    view.data_prevista = '{{ $manutencao->data_prevista ? $manutencao->data_prevista->format('d/m/Y') : '—' }}';
                                                    view.descricao = @js($manutencao->descricao);
                                                    view.status_label = '{{ $statusLabel }}';
                                                    view.status_badge_class = '{{ $badgeClass }}';
                                                    openView = true;
                                                ">
                                                Ver
                                            </button>

                                            @role('admin|gestor')
                                                {{-- Editar --}}
                                                <a href="{{ route('manutencoes.preventivas.edit', $manutencao) }}"
                                                   class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white text-xs font-semibold">
                                                    Editar
                                                </a>

                                                {{-- Concluir (se ainda não concluída) --}}
                                                @if($status !== 'concluida')
                                                    <form method="POST"
                                                          action="{{ route('manutencoes.preventivas.concluir', $manutencao) }}"
                                                          class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="text-verdes-verde_folha hover:text-verdes-verde_claro dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-xs font-semibold"
                                                                onclick="return confirm('Marcar esta manutenção como concluída?')">
                                                            Concluir
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Excluir --}}
                                                <form method="POST"
                                                      action="{{ route('manutencoes.preventivas.destroy', $manutencao) }}"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-semibold"
                                                            onclick="return confirm('Tem certeza que deseja excluir esta manutenção?')">
                                                        Excluir
                                                    </button>
                                                </form>
                                            @endrole
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhuma manutenção preventiva cadastrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

@role('admin|gestor')
{{-- Modal de inclusão (teleportado pro body) --}}
<template x-teleport="body">
    <div
        x-show="openCreate"
        x-cloak
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50"
    >
        <div
            @click.away="openCreate = false"
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-white/10 shadow-lg w-full max-w-lg mx-4"
        >
            <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Incluir Manutenção Preventiva
                </h3>
                <button
                    type="button"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    @click="openCreate = false"
                >
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('manutencoes.preventivas.store') }}" class="px-6 py-4 space-y-4">
                @csrf

                {{-- Equipamento --}}
                <div>
                    <label for="equipamento_id"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Equipamento <span class="text-red-500">*</span>
                    </label>
                    <select id="equipamento_id" name="equipamento_id" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                   text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                        <option value="">Selecione um equipamento</option>
                        @foreach($equipamentos as $equipamento)
                            <option value="{{ $equipamento->id }}" @selected(old('equipamento_id') == $equipamento->id)>
                                {{ $equipamento->nome }}
                                @if($equipamento->setor) - {{ $equipamento->setor->nome }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('equipamento_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Data prevista (opcional) --}}
                <div>
                    <label for="data_prevista"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Data prevista
                    </label>
                    <input type="date" id="data_prevista" name="data_prevista"
                           value="{{ old('data_prevista') }}"
                           class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                  text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                    @error('data_prevista')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descrição / o que deve ser feito --}}
                <div>
                    <label for="descricao"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        O que deve ser feito? <span class="text-red-500">*</span>
                    </label>
                    <textarea id="descricao" name="descricao" rows="4" required
                              class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                     text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                              placeholder="Ex.: Verificar rolamentos da esteira, lubrificar correntes, apertar parafusos, etc.">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botões --}}
                <div class="mt-4 flex justify-end gap-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <button type="button"
                            @click="openCreate = false"
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                   text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                   hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                   text-xs font-semibold text-white uppercase tracking-widest
                                   hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
@endrole
{{-- Modal de VISUALIZAÇÃO (teleportado pro body) --}}
<template x-teleport="body">
    <div
        x-show="openView"
        x-cloak
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50"
    >
        <div
            @click.away="openView = false"
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-white/10 shadow-lg w-full max-w-lg mx-4"
        >
            <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Detalhes da Manutenção Preventiva
                </h3>
                <button
                    type="button"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    @click="openView = false"
                >
                    ✕
                </button>
            </div>

            <div class="px-6 py-4 space-y-4 text-gray-900 dark:text-gray-100">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                        Equipamento
                    </p>
                    <p class="mt-0.5 text-sm" x-text="view.equipamento"></p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                        Setor
                    </p>
                    <p class="mt-0.5 text-sm" x-text="view.setor"></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                            Data prevista
                        </p>
                        <p class="mt-0.5 text-sm" x-text="view.data_prevista"></p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">
                            Status
                        </p>
                        <p class="mt-0.5 text-sm">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="view.status_badge_class"
                                x-text="view.status_label">
                            </span>
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        O que deve ser feito
                    </h4>
                    <div class="max-h-60 overflow-y-auto pr-1">
                        <p class="text-sm whitespace-pre-line break-words" x-text="view.descricao"></p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button
                    type="button"
                    @click="openView = false"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                           text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                           hover:bg-gray-50 dark:hover:bg-gray-700">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</template>


    {{-- FullCalendar CSS/JS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    {{-- Ajustes de tema para o FullCalendar --}}
    <style>
        #calendar .fc-button-primary {
            background-color: #8dc63f;
            border-color: #63be15;
            color: #fff;
        }

        #calendar .fc-button-primary:hover {
            background-color: #63be15;
        }

        #calendar .fc-button-primary:disabled {
            background-color: #cfe8a0;
            border-color: #cfe8a0;
            color: #4b5b2a;
        }

        #calendar .fc-day-today {
            background-color: rgba(141, 198, 63, 0.15);
        }

        .dark #calendar .fc {
            background-color: #111827; /* bg-gray-900 */
            color: #e5e7eb;           /* text-gray-200 */
        }

        .dark #calendar .fc-theme-standard td,
        .dark #calendar .fc-theme-standard th,
        .dark #calendar .fc-scrollgrid {
            border-color: #374151;    /* gray-700 */
        }

        .dark #calendar .fc-col-header-cell-cushion,
        .dark #calendar .fc-daygrid-day-number,
        .dark #calendar .fc-toolbar-title,
        .dark #calendar .fc-daygrid-day-top {
            color: #e5e7eb;           /* text-gray-200 */
        }

        .dark #calendar .fc-button-primary {
            background-color: #63be15;
            border-color: #8dc63f;
            color: #f8fafc;
        }

        .dark #calendar .fc-button-primary:hover {
            background-color: #8dc63f;
        }

        .dark #calendar .fc-button-primary:disabled {
            background-color: #335f1a;
            border-color: #335f1a;
            opacity: 0.8;
            color: #e5e7eb;
        }

        .dark #calendar .fc-day-today {
            background-color: rgba(141, 198, 63, 0.25);
        }

        .dark #calendar .fc-list,
        .dark #calendar .fc-list-empty {
            background-color: #111827;
            color: #e5e7eb;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                },
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia',
                    list: 'Lista',
                },
                locale: 'pt-br',
                height: 'auto',

                events: '{{ route('manutencoes.preventivas.events') }}',

                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },

                eventDidMount: function(info) {
                    info.el.setAttribute('title', info.event.title);
                },
            });

            calendar.render();
        });
    </script>
</x-app-layout>
