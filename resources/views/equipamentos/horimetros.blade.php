<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Horímetro dos Equipamentos') }}
        </h2>
    </x-slot>

    <div class="py-8" x-data="horimetroPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Toast de sucesso --}}
            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 3200)"
                    x-show="show"
                    x-transition
                    class="rounded-lg border border-verdes-verde_claro/30 bg-verdes-verde_claro/10 px-4 py-3 text-sm text-verdes-verde_escuro shadow-sm dark:border-verdes-verde_claro/30 dark:bg-verdes-verde_claro/15 dark:text-verdes-verde_claro">
                    {{ session('success') }}
                </div>
            @endif

            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Lançamento de horímetro
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Visualize a vida útil planejada e o horímetro atual de cada equipamento.
                        </p>
                    </div>
                    <div class="flex flex-col items-start gap-3 md:flex-row md:items-center md:gap-3">
                        <span class="inline-flex items-center rounded-full bg-verdes-verde_claro/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-widest text-verdes-verde_escuro dark:bg-verdes-verde_claro/15 dark:text-verdes-verde_claro">
                            Total: {{ $equipamentos->count() }} equipamentos
                        </span>
                        <button
                            type="button"
                            id="btn-open-alert"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                   font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Criar Aviso
                        </button>
                    </div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1 w-full bg-verdes-verde_claro/30"></div>
                <div class="px-6 py-4">
                    <form method="GET" action="{{ route('equipamentos.horimetros') }}"
                          class="grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">
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
                                        {{ $setor->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Buscar por nome ou código
                            </label>
                            <input id="search" name="search" type="text"
                                   value="{{ request('search') }}"
                                   placeholder="Ex.: TR-09 ou Trator"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                        </div>

                        <div class="flex gap-2 md:justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                           font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                Filtrar
                            </button>
                            <a href="{{ route('equipamentos.horimetros') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md
                                      font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-200 dark:hover:bg-gray-600">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-verdes-verde_claro/10 dark:bg-verdes-verde_claro/10">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Equipamento
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Vida útil (h)
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Horímetro atual
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Próxima manutenção
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($equipamentos as $equipamento)
                                <tr class="hover:bg-verdes-verde_claro/5 dark:hover:bg-verdes-verde_claro/10 transition"
                                    data-equip-id="{{ $equipamento->id }}"
                                    data-vida-util="{{ $equipamento->vida_util_h ?? '' }}">
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $equipamento->codigo ?? $equipamento->id }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $equipamento->nome }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $equipamento->vida_util_h !== null ? number_format($equipamento->vida_util_h, 0, ',', '.') . ' h' : '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        @php
                                            $vida = $equipamento->vida_util_h ?: null;
                                            $h    = $equipamento->horimetro ?? 0;
                                            $ratio = $vida ? ($h / max($vida, 0.0001)) : null;
                                            $badge = 'bg-gray-200 text-gray-800';
                                            if ($ratio !== null) {
                                                if ($ratio >= 0.85)      $badge = 'bg-red-100 text-red-800';
                                                elseif ($ratio >= 0.6)   $badge = 'bg-yellow-100 text-yellow-800';
                                                else                     $badge = 'bg-green-100 text-green-800';
                                            }
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold js-horimetro-badge {{ $badge }}"
                                                  data-current-value="{{ $equipamento->horimetro ?? 0 }}">
                                                {{ $equipamento->horimetro !== null ? number_format($equipamento->horimetro, 2, ',', '.') . ' h' : '—' }}
                                            </span>
                                            @if($vida)
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        @php
                                            $alerta = $equipamento->alertas->first();
                                        @endphp
                                        @if($alerta)
                                            @if($alerta->tipo === 'data')
                                                @if($alerta->recorrente && $alerta->dias_recorrencia)
                                                    @php
                                                        $inicio = \Illuminate\Support\Carbon::parse($alerta->data_inicio_recorrencia ?? $alerta->created_at);
                                                        $diasRec = max(1, (int) $alerta->dias_recorrencia);
                                                        $hoje = now();
                                                        if ($hoje->lessThanOrEqualTo($inicio)) {
                                                            $proxima = $inicio;
                                                        } else {
                                                            $passados = $inicio->diffInDays($hoje);
                                                            $intervalos = intdiv($passados, $diasRec) + 1;
                                                            $proxima = $inicio->copy()->addDays($intervalos * $diasRec);
                                                        }
                                                    @endphp
                                                    <div class="text-xs text-blue-800 dark:text-blue-300">
                                                        A cada {{ $alerta->dias_recorrencia }} dias<br>
                                                        Próxima: {{ $proxima->format('d/m/Y') }}
                                                    </div>
                                                @elseif($alerta->data_alerta)
                                                    {{ \Illuminate\Support\Carbon::parse($alerta->data_alerta)->format('d/m/Y') }}
                                                @else
                                                    —
                                                @endif
                                            @else
                                                <div class="text-xs text-gray-600 dark:text-gray-300">
                                                <strong>{{ number_format($alerta->horimetro_alvo, 2, ',', '.') }} h</strong>
                                                </div>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-sm">
                                        <div class="flex justify-end items-center gap-2">
                                            <button
                                                type="button"
                                                data-id="{{ $equipamento->id }}"
                                                data-nome="{{ $equipamento->nome }}"
                                                data-codigo="{{ $equipamento->codigo ?? $equipamento->id }}"
                                                data-atual="{{ $equipamento->horimetro ?? '' }}"
                                                @click="openHorimetroModalFromButton($event.currentTarget)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-verdes-verde_claro border border-transparent
                                                       text-xs font-semibold text-white uppercase tracking-widest shadow-sm whitespace-nowrap
                                                       hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                                                </svg>
                                                Horímetro
                                            </button>
                                            @if($alerta)
                                                <button
                                                    type="button"
                                                    class="btn-edit-alert inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-verdes-verde_claro/10 border border-verdes-verde_claro/30
                                                           text-[11px] font-semibold text-verdes-verde_escuro uppercase tracking-widest whitespace-nowrap
                                                           hover:bg-verdes-verde_claro/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro
                                                           dark:bg-verdes-verde_claro/15 dark:text-verdes-verde_claro dark:border-verdes-verde_claro/30 dark:hover:bg-verdes-verde_claro/25"
                                                    data-alert-id="{{ $alerta->id }}"
                                                    data-equip-id="{{ $equipamento->id }}"
                                                    data-tipo="{{ $alerta->tipo }}"
                                                    data-recorrente="{{ $alerta->recorrente ? 1 : 0 }}"
                                                    data-dias="{{ $alerta->dias_recorrencia ?? '' }}"
                                                    data-data-inicio="{{ $alerta->data_inicio_recorrencia ? $alerta->data_inicio_recorrencia->format('Y-m-d') : '' }}"
                                                    data-data-alerta="{{ $alerta->data_alerta ? $alerta->data_alerta->format('Y-m-d') : '' }}"
                                                    data-horimetro-alvo="{{ $alerta->horimetro_alvo ?? '' }}"
                                                    data-mensagem="{{ e($alerta->mensagem ?? '') }}"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-9.193 9.193a1 1 0 01-.465.263l-3.293.823a.5.5 0 01-.606-.606l.823-3.293a1 1 0 01.263-.465l9.193-9.193z" />
                                                    </svg>
                                                    Editar Aviso
                                                </button>
                                            @endif
                                            <form method="POST"
                                                  action="{{ route('equipamentos.horimetro.zerar', $equipamento) }}"
                                                  class="inline js-zerar-horimetro-form"
                                                  data-equip-id="{{ $equipamento->id }}"
                                                  data-confirm-message="Zerar horímetro deste equipamento após manutenção?">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white border border-gray-300
                                                           text-xs font-semibold text-gray-700 uppercase tracking-widest shadow-sm whitespace-nowrap
                                                           hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300
                                                           dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Zerar Horímetro
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhum equipamento cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Modal Horímetro --}}
        <div
            x-cloak
            x-show="horimetroOpen"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
            style="display: none;"
        >
            <div
                x-transition.scale
                class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900 dark:border dark:border-white/10">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Equipamento</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="equipamento.nome"></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Código: <span x-text="equipamento.codigo"></span>
                            </p>
                        </div>
                        <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            ✕
                        </button>
                    </div>

                    <form id="horimetro-form" :action="routeSubmit" method="POST" @submit.prevent="submitHorimetro($event)" class="mt-6 space-y-4">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Horas a lançar (será somado ao horímetro atual)
                    </label>
                    <input
                        name="horimetro"
                        type="number"
                        min="0"
                        step="0.01"
                        x-model="valor"
                        required
                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                               text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Horímetro atual: <span x-text="equipamento.atualDisplay"></span><br>
                        Horímetro após lançamento: <span x-text="valor ? previewTotal() : '—'"></span>
                    </p>

                    <p x-show="errorMsg"
                       x-text="errorMsg"
                       class="text-xs font-medium text-red-600 dark:text-red-400"></p>

                    <div class="flex justify-end gap-2 pt-3">
                        <button type="button"
                                @click="closeModal()"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                       text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                       hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancelar
                        </button>
                        <button type="submit"
                                :disabled="isSubmitting"
                                class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                       text-xs font-semibold text-white uppercase tracking-widest disabled:opacity-60 disabled:cursor-not-allowed
                                       hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Salvar
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function horimetroPage() {
            return {
                // Horímetro modal state
                horimetroOpen: false,
                equipamento: { id: null, nome: '', codigo: '', atual: null, atualDisplay: '—' },
                valor: null,
                routeSubmit: '',
                isSubmitting: false,
                errorMsg: '',
                formatHoras(value) {
                    const parsed = Number(value);
                    if (value === null || value === undefined || Number.isNaN(parsed)) {
                        return '—';
                    }

                    return `${parsed.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} h`;
                },
                getBadgeClass(horimetro, vidaUtil) {
                    if (vidaUtil === null || vidaUtil === undefined || Number(vidaUtil) <= 0) {
                        return 'bg-gray-200 text-gray-800';
                    }

                    const ratio = Number(horimetro ?? 0) / Math.max(Number(vidaUtil), 0.0001);
                    if (ratio >= 0.85) return 'bg-red-100 text-red-800';
                    if (ratio >= 0.6) return 'bg-yellow-100 text-yellow-800';
                    return 'bg-green-100 text-green-800';
                },
                openHorimetroModalFromButton(button) {
                    if (!button) return;

                    const atualRaw = button.dataset.atual;
                    const atual = atualRaw === '' || atualRaw === undefined ? null : Number(atualRaw);

                    this.openHorimetroModal({
                        id: Number(button.dataset.id),
                        nome: button.dataset.nome ?? '',
                        codigo: button.dataset.codigo ?? '',
                        atual,
                    });
                },
                openHorimetroModal(data) {
                    this.equipamento = {
                        ...data,
                        atualDisplay: this.formatHoras(data.atual),
                    };
                    this.valor = null;
                    this.errorMsg = '';
                    this.routeSubmit = `{{ url('/equipamentos') }}/${data.id}/horimetro`;
                    this.horimetroOpen = true;
                },
                closeModal() {
                    this.horimetroOpen = false;
                    this.valor = null;
                    this.errorMsg = '';
                },
                previewTotal() {
                    const atual = this.equipamento.atual ?? 0;
                    const add = Number(this.valor ?? 0);
                    return this.formatHoras(atual + add);
                },
                applyHorimetroUpdate(payload) {
                    const equipamentoId = Number(payload?.equipamento_id);
                    if (!equipamentoId) return;

                    const novoHorimetro = Number(payload?.horimetro ?? 0);
                    const row = document.querySelector(`tr[data-equip-id="${equipamentoId}"]`);
                    if (!row) return;

                    if (payload?.vida_util_h !== undefined && payload?.vida_util_h !== null) {
                        row.dataset.vidaUtil = String(payload.vida_util_h);
                    }

                    const vidaUtilRaw = row.dataset.vidaUtil;
                    const vidaUtil = vidaUtilRaw === '' || vidaUtilRaw === undefined ? null : Number(vidaUtilRaw);
                    const badge = row.querySelector('.js-horimetro-badge');
                    if (badge) {
                        badge.className = `inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold js-horimetro-badge ${this.getBadgeClass(novoHorimetro, vidaUtil)}`;
                        badge.dataset.currentValue = String(novoHorimetro);
                        badge.textContent = this.formatHoras(novoHorimetro);
                    }

                    const launchButton = row.querySelector('button[data-id]');
                    if (launchButton) {
                        launchButton.dataset.atual = String(novoHorimetro);
                    }

                    if (Number(this.equipamento?.id) === equipamentoId) {
                        this.equipamento.atual = novoHorimetro;
                        this.equipamento.atualDisplay = this.formatHoras(novoHorimetro);
                    }
                },
                async submitHorimetro(event) {
                    const form = event.target;
                    if (!form || this.isSubmitting) return;

                    this.errorMsg = '';
                    this.isSubmitting = true;
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(form),
                        });

                        const result = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            const firstError = result?.errors ? Object.values(result.errors)[0]?.[0] : null;
                            throw new Error(firstError || result?.message || 'Não foi possível atualizar o horímetro.');
                        }

                        this.applyHorimetroUpdate(result?.data ?? {});
                        this.closeModal();
                    } catch (error) {
                        this.errorMsg = error?.message || 'Não foi possível atualizar o horímetro.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                // Alerta modal state
                alert: {
                    open: false,
                    equipamentoId: '',
                    mensagem: '',
                    tipo: 'data',
                    recorrente: false,
                    dias: null,
                    data: '',
                    horimetroAlvo: null,
                },
                openAlertModal() {
                    this.alert.open = true;
                },
                alertClose() {
                    this.alert.open = false;
                    this.alert.equipamentoId = '';
                    this.alert.mensagem = '';
                    this.alert.tipo = 'data';
                    this.alert.recorrente = false;
                    this.alert.dias = null;
                    this.alert.data = '';
                    this.alert.horimetroAlvo = null;
                },
            }
        }
    </script>

    {{-- Modal Alerta de Manutenção --}}
    <div id="alert-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900 dark:border dark:border-white/10 transform transition-all duration-150 scale-100 translate-y-0">
            <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
            <div class="p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p id="alert-modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100">Criar Aviso</p>
                    </div>
                    <button type="button" data-close-alert class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        ✕
                    </button>
                </div>

            <form id="alert-form" class="mt-6 space-y-4" method="POST" action="{{ route('equipamentos.alertas.store') }}">
                @csrf
                <input type="hidden" name="_method" id="alert-method" value="" disabled>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Equipamento
                        </label>
                        <select id="alert-equip" name="equipamento_id" required
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                       text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            <option value="">Selecione</option>
                            @foreach ($equipamentos as $equip)
                                <option value="{{ $equip->id }}">{{ $equip->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tipo de aviso
                        </label>
                        <select id="alert-tipo" name="tipo" required
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                       text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            <option value="data">Por data</option>
                            <option value="horimetro">Por horímetro</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Mensagem do aviso
                    </label>
                    <textarea id="alert-mensagem" name="mensagem" rows="2"
                              class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                     text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                              placeholder="Ex.: Revisar filtros, trocar óleo..."></textarea>
                </div>

                        <div id="alert-data-block" class="space-y-3">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" id="alert-recorrente" name="recorrente" value="1"
                                       class="rounded border-gray-300 text-verdes-verde_claro shadow-sm focus:ring-verdes-verde_claro">
                                Recorrente
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div id="alert-inicio-field">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Data inicial
                                    </label>
                                    <input id="alert-data-inicio" type="date" name="data_inicio_recorrencia"
                                           class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                  text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                </div>
                                <div id="alert-recorrencia-field">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Dias para recorrência
                                    </label>
                                    <input id="alert-dias-recorrencia" type="number" min="1" step="1" name="dias_recorrencia"
                                           class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                                  text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                           placeholder="Ex.: 30">
                                </div>
                                <div id="alert-data-field">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data do aviso
                            </label>
                            <input id="alert-data-alerta" type="date" name="data_alerta"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                        </div>
                    </div>
                </div>

                <div id="alert-horimetro-block" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Horímetro alvo
                        </label>
                        <input id="alert-horimetro-alvo" type="number" min="0" step="0.01" name="horimetro_alvo"
                               class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                      text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                               placeholder="Ex.: 250.00">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3">
                    <button type="button"
                            data-close-alert
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                   text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                   hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button id="alert-submit" type="submit"
                            class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                   text-xs font-semibold text-white uppercase tracking-widest
                                   hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                        Salvar aviso
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('alert-modal');
            const btnOpen = document.getElementById('btn-open-alert');
            const form = document.getElementById('alert-form');
            const methodInput = document.getElementById('alert-method');
            const title = document.getElementById('alert-modal-title');
            const submitBtn = document.getElementById('alert-submit');
            const mensagem = document.getElementById('alert-mensagem');
            const closeButtons = modal?.querySelectorAll('[data-close-alert]') ?? [];
            const editButtons = document.querySelectorAll('.btn-edit-alert');
            const tipo = document.getElementById('alert-tipo');
            const recorrente = document.getElementById('alert-recorrente');
            const equipSelect = document.getElementById('alert-equip');
            const dataInicio = document.getElementById('alert-data-inicio');
            const diasRecorrencia = document.getElementById('alert-dias-recorrencia');
            const dataAlerta = document.getElementById('alert-data-alerta');
            const horimetroAlvo = document.getElementById('alert-horimetro-alvo');
            const blocoData = document.getElementById('alert-data-block');
            const blocoRecorrencia = document.getElementById('alert-recorrencia-field');
            const blocoDataUnica = document.getElementById('alert-data-field');
            const blocoInicio = document.getElementById('alert-inicio-field');
            const blocoHorimetro = document.getElementById('alert-horimetro-block');
            const storeUrl = @js(route('equipamentos.alertas.store'));
            const updateBaseUrl = @js(url('/equipamentos/alertas'));

            const syncVisibilidade = () => {
                if (!tipo || !recorrente) return;

                const isData = tipo.value === 'data';
                const isRecorrente = recorrente.checked;

                blocoData?.classList.toggle('hidden', !isData);
                blocoHorimetro?.classList.toggle('hidden', isData);

                blocoRecorrencia?.classList.toggle('hidden', !(isData && isRecorrente));
                blocoDataUnica?.classList.toggle('hidden', !(isData && !isRecorrente));
                blocoInicio?.classList.toggle('hidden', !(isData && isRecorrente));
            };

            const openModal = () => {
                modal?.classList.remove('hidden');
                modal?.classList.add('flex');
            };

            const closeModal = () => {
                modal?.classList.add('hidden');
                modal?.classList.remove('flex');
            };

            const setCreateMode = () => {
                if (!form) return;
                form.action = storeUrl;
                if (methodInput) {
                    methodInput.value = '';
                    methodInput.disabled = true;
                }
                if (title) title.textContent = 'Criar Aviso';
                if (submitBtn) submitBtn.textContent = 'Salvar aviso';
                if (equipSelect) equipSelect.value = '';
                if (tipo) tipo.value = 'data';
                if (recorrente) recorrente.checked = false;
                if (mensagem) mensagem.value = '';
                if (dataInicio) dataInicio.value = '';
                if (diasRecorrencia) diasRecorrencia.value = '';
                if (dataAlerta) dataAlerta.value = '';
                if (horimetroAlvo) horimetroAlvo.value = '';
                syncVisibilidade();
            };

            const setEditMode = (data) => {
                if (!form) return;
                form.action = `${updateBaseUrl}/${data.alertId}`;
                if (methodInput) {
                    methodInput.value = 'PUT';
                    methodInput.disabled = false;
                }
                if (title) title.textContent = 'Editar Aviso';
                if (submitBtn) submitBtn.textContent = 'Atualizar aviso';
                if (equipSelect) equipSelect.value = data.equipId ?? '';
                if (tipo) tipo.value = data.tipo ?? 'data';
                if (recorrente) recorrente.checked = data.recorrente === '1';
                if (mensagem) mensagem.value = data.mensagem ?? '';
                if (dataInicio) dataInicio.value = data.dataInicio ?? '';
                if (diasRecorrencia) diasRecorrencia.value = data.dias ?? '';
                if (dataAlerta) dataAlerta.value = data.dataAlerta ?? '';
                if (horimetroAlvo) horimetroAlvo.value = data.horimetroAlvo ?? '';
                syncVisibilidade();
            };

            btnOpen?.addEventListener('click', () => {
                setCreateMode();
                openModal();
            });

            closeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    closeModal();
                });
            });

            editButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    setEditMode({
                        alertId: btn.dataset.alertId,
                        equipId: btn.dataset.equipId,
                        tipo: btn.dataset.tipo,
                        recorrente: btn.dataset.recorrente,
                        dias: btn.dataset.dias,
                        dataInicio: btn.dataset.dataInicio,
                        dataAlerta: btn.dataset.dataAlerta,
                        horimetroAlvo: btn.dataset.horimetroAlvo,
                        mensagem: btn.dataset.mensagem,
                    });
                    openModal();
                });
            });

            tipo?.addEventListener('change', syncVisibilidade);
            recorrente?.addEventListener('change', syncVisibilidade);

            const pageRoot = document.querySelector('div.py-8[x-data]');
            const pageData = pageRoot
                ? (window.Alpine?.$data?.(pageRoot) ?? pageRoot.__x?.$data ?? null)
                : null;
            const zerarForms = document.querySelectorAll('.js-zerar-horimetro-form');

            const extractErrorMessage = (result, fallback) => {
                const firstError = result?.errors ? Object.values(result.errors)[0]?.[0] : null;
                return firstError || result?.message || fallback;
            };

            zerarForms.forEach(formEl => {
                formEl.addEventListener('submit', async (event) => {
                    if (event.defaultPrevented) return;
                    event.preventDefault();

                    const confirmMessage = formEl.dataset.confirmMessage || 'Zerar horímetro deste equipamento após manutenção?';
                    if (!window.confirm(confirmMessage)) return;

                    const submitBtn = formEl.querySelector('button[type="submit"]');
                    submitBtn?.setAttribute('disabled', 'disabled');
                    try {
                        const response = await fetch(formEl.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(formEl),
                        });

                        const result = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(extractErrorMessage(result, 'Não foi possível zerar o horímetro.'));
                        }

                        pageData?.applyHorimetroUpdate(result?.data ?? {});
                    } catch (error) {
                        window.alert(error?.message || 'Não foi possível zerar o horímetro.');
                    } finally {
                        submitBtn?.removeAttribute('disabled');
                    }
                });
            });

            // Estado inicial
            syncVisibilidade();
        });
    </script>
</x-app-layout>
