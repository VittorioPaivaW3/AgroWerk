<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ordens de Servico') }}
        </h2>
    </x-slot>

    <div class="py-8" x-data="ordensPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Todas as ordens
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Acompanhe o status e a distribuicao das OS.
                        </p>
                    </div>

                    @role('admin|gestor')
                        <a href="{{ route('ordens.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-verdes-verde_folha
                                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Nova OS
                        </a>
                    @endrole
                </div>
            </div>

            {{-- Filtros --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1 w-full bg-verdes-verde_claro/30"></div>
                <form method="GET" action="{{ route('ordens.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 px-6 py-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="data">Data</label>
                        <input type="date" id="data" name="data"
                               value="{{ request('data') }}"
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="setor_id">Setor</label>
                        <select id="setor_id" name="setor_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            <option value="">Todos</option>
                            @foreach($setores ?? [] as $setor)
                                <option value="{{ $setor->id }}" @selected(request('setor_id') == $setor->id)>{{ $setor->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tecnico_id">Tecnico</label>
                        <select id="tecnico_id" name="tecnico_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            <option value="">Todos</option>
                            @foreach($tecnicos ?? [] as $tec)
                                <option value="{{ $tec->id }}" @selected(request('tecnico_id') == $tec->id)>{{ $tec->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="concluida">Status</label>
                        @php
                            $concluidaFiltro = request()->has('concluida')
                                ? request('concluida')
                                : '0';
                        @endphp
                        <select id="concluida" name="concluida"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            <option value="" @selected($concluidaFiltro === '')>Todas</option>
                            <option value="1" @selected($concluidaFiltro === '1')>Concluidas</option>
                            <option value="0" @selected($concluidaFiltro === '0')>Nao concluidas</option>
                        </select>
                    </div>

                    <div class="sm:col-span-4 flex gap-2 justify-end">
                        <a href="{{ route('ordens.index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                            Limpar
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-md bg-verdes-verde_claro text-white text-sm font-semibold hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabela --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-verdes-verde_claro/10 dark:bg-verdes-verde_claro/10">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Codigo</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setor</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Equipamento</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tecnico atribuido</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Criada em</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acoes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($ordens as $os)
                                @php
                                    $prioridadeRaw = $os->prioridade ?? null;
                                    $prioridade = $prioridadeRaw ? strtolower(trim($prioridadeRaw)) : null;

                                    $statusRaw = $os->status ?? null;
                                    $status = $statusRaw ? strtolower(trim($statusRaw)) : null;

                                    $statusLabel = match ($status) {
                                        'aberta'       => 'ABERTA',
                                        'em_execucao'  => 'EM EXECUCAO',
                                        'concluida'    => 'CONCLUIDA',
                                        'cancelada'    => 'CANCELADA',
                                        default        => ($statusRaw ? strtoupper(str_replace('_', ' ', $statusRaw)) : '-'),
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

                                    $rowPriorityClass = match ($prioridade) {
                                        'muito_alto' => 'border-l-4 border-red-700',
                                        'alto'       => 'border-l-4 border-red-600',
                                        'medio'      => 'border-l-4 border-yellow-400',
                                        'baixo'      => 'border-l-4 border-verdes-verde_claro',
                                        default      => 'border-l-4 border-gray-300',
                                    };

                                    $priorityBadgeClass = match ($prioridade) {
                                        'muito_alto', 'alto' => 'bg-red-500',
                                        'medio'             => 'bg-yellow-400',
                                        'baixo'             => 'bg-verdes-verde_claro',
                                        default             => 'bg-gray-200',
                                    };

                                    $setorNome = $os->setor->nome
                                        ?? $os->equipamento->setor->nome
                                        ?? '-';
                                @endphp

                                <tr class="{{ $rowPriorityClass }} transition-colors hover:bg-verdes-verde_claro/5 dark:hover:bg-verdes-verde_claro/10">
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-md {{ $priorityBadgeClass }}"
                                                  title="Status {{ $statusLabel }}" aria-label="Status {{ $statusLabel }}">
                                                <img src="{{ asset($statusIcon) }}"
                                                     alt="Status {{ $statusLabel }}"
                                                     class="h-4 w-4 object-contain dark:hidden">
                                                <img src="{{ asset($statusIconDark) }}"
                                                     alt="Status {{ $statusLabel }}"
                                                     class="hidden h-4 w-4 object-contain dark:block">
                                            </span>
                                            <span class="font-semibold">#{{ $os->codigo ?? $os->id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $setorNome }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->equipamento->nome ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        @if($os->tecnicos->count())
                                            {{ $os->tecnicos->pluck('name')->join(', ') }}
                                        @else
                                            <span class="text-gray-500">Sem tecnico</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $os->created_at?->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('ordens.show', $os) }}"
                                               class="text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-xs font-semibold">
                                                Ver
                                            </a>

                                            @role('admin')
                                                <a href="{{ route('ordens.edit', $os) }}"
                                                   class="text-xs font-semibold text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white">
                                                    Editar
                                                </a>
                                            @endrole

                                            @role('admin|gestor')
                                                @if($status !== 'em_execucao' && $status !== 'concluida')
                                                    <button type="button"
                                                        class="text-xs font-semibold text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha"
                                                        @click="openModal({
                                                            id: {{ $os->id }},
                                                            codigo: @json($os->codigo ?? $os->id),
                                                            tecnicos: @json($os->tecnicos->pluck('id')),
                                                            gestores: @json($os->gestores->pluck('id')),
                                                            equip_terceiro: @json($os->equipamento->terceiro ?? false)
                                                        })">
                                                        Atribuir tecnico
                                                    </button>
                                                @endif
                                            @endrole

                                            @php
                                                $user = auth()->user();
                                                $isAdminOuGestor = ($user->hasRole('admin') ?? false) || ($user->hasRole('gestor') ?? false);
                                            @endphp
                                            @if($status === 'concluida' && $isAdminOuGestor)
                                                @if(!is_null($os->custo_total))
                                                    <span class="inline-flex items-center px-2.5 py-1 bg-verdes-verde_claro/10 border border-verdes-verde_claro/30 rounded-md text-[11px] font-semibold text-verdes-verde_escuro uppercase tracking-widest">
                                                        Custo atribuido
                                                    </span>
                                                @else
                                                    <button type="button"
                                                        class="inline-flex items-center px-2.5 py-1 bg-verdes-verde_claro/15 border border-verdes-verde_claro/30 rounded-md text-[11px] font-semibold text-verdes-verde_escuro uppercase tracking-widest hover:bg-verdes-verde_claro/25"
                                                        @click="openCustoModal({
                                                            id: {{ $os->id }},
                                                            codigo: @json($os->codigo ?? $os->id),
                                                            custo: @json($os->custo_total)
                                                        })">
                                                        Atribuir custo
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhuma ordem de servico encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $ordens->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal atribuição --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;" @keydown.escape.window="closeModal()">
            <div class="absolute inset-0 bg-black/40" @click="closeModal()"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-white/10 border-t-4 border-t-verdes-verde_claro shadow-xl w-full max-w-lg p-6 space-y-4" @click.stop>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Atribuir tecnico/gestor</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">OS #<span x-text="codigo"></span></p>
                    </div>
                    <button type="button" class="text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white" @click="closeModal()">&times;</button>
                </div>

                <form method="POST" :action="ordemId ? `/ordens/${ordemId}/atribuir` : '#'" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2">Tecnicos</h4>
                            <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
                                <template x-for="tec in tecnicosDisponiveis" :key="tec.id">
                                    <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-100">
                                        <input type="checkbox" :value="tec.id" name="tecnicos[]" class="rounded border-gray-300 text-verdes-verde_claro focus:ring-verdes-verde_claro" x-model="selecionadosTecnicos">
                                        <span x-text="tec.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-2">Gestores</h4>
                            <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
                                <template x-for="ges in gestoresDisponiveis" :key="ges.id">
                                    <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-100">
                                        <input type="checkbox" :value="ges.id" name="gestores[]" class="rounded border-gray-300 text-verdes-verde_claro focus:ring-verdes-verde_claro" x-model="selecionadosGestores">
                                        <span x-text="ges.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <template x-if="equipamentoTerceiro">
                            <div class="sm:col-span-2 mt-2 border-t border-gray-200 dark:border-gray-700 pt-3">
                                <label class="inline-flex items-start gap-2">
                                    <input type="checkbox" name="atribuir_terceiros" value="1" class="mt-1 rounded border-gray-300 text-red-600 focus:ring-red-500" x-model="atribuirTerceiros">
                                    <span class="text-sm text-gray-700 dark:text-gray-200">
                                        Resolver esta OS via <strong>terceiros</strong>.
                                        <span class="block text-xs text-red-600 mt-1">
                                            Ao salvar, a OS sera marcada como CONCLUIDA (terceiros).
                                        </span>
                                    </span>
                                </label>
                                <template x-if="showConfirmTerceiros">
                                    <div class="mt-3 rounded-md border border-red-300 bg-red-50 dark:border-red-700 dark:bg-red-900/30 p-3 text-xs text-red-800 dark:text-red-200">
                                        Esta OS sera marcada como concluida por terceiros. Deseja prosseguir?
                                        <div class="mt-2 flex gap-2">
                                            <button type="button" class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600" @click="cancelTerceiros()">Cancelar</button>
                                            <button type="button" class="px-3 py-1 rounded-md bg-red-600 text-white" @click="confirmTerceiros()">Confirmar</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="closeModal()">Cancelar</button>
                        <button type="submit" class="px-4 py-2 rounded-md bg-verdes-verde_claro text-white text-sm font-semibold hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">Salvar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal custo --}}
        <div x-show="openCusto" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;" @keydown.escape.window="closeCustoModal()">
            <div class="absolute inset-0 bg-black/40" @click="closeCustoModal()"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-white/10 border-t-4 border-t-verdes-verde_claro shadow-xl w-full max-w-md p-6 space-y-4" @click.stop>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">Atribuir custo</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">OS #<span x-text="codigoCusto"></span></p>
                    </div>
                    <button type="button" class="text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white" @click="closeCustoModal()">&times;</button>
                </div>

                <form method="POST" :action="custoAction">
                    @csrf
                    @method('PUT')
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="custo_total_modal">Custo total</label>
                    <div class="relative rounded-md shadow-sm">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm">R$</span>
                        <input id="custo_total_modal" name="custo_total" type="number" step="0.01" min="0" x-model="custoTotal"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:ring-verdes-verde_claro focus:border-verdes-verde_claro"
                               required>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="closeCustoModal()">Cancelar</button>
                        <button type="submit" class="px-4 py-2 rounded-md bg-verdes-verde_claro text-white text-sm font-semibold hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">Salvar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ordensPage', () => ({
                // modal atribuir
                open: false,
                ordemId: null,
                codigo: '',
                tecnicosDisponiveis: @json($tecnicos ?? []),
                gestoresDisponiveis: @json($gestores ?? []),
                selecionadosTecnicos: [],
                selecionadosGestores: [],
                equipamentoTerceiro: false,
                atribuirTerceiros: false,
                showConfirmTerceiros: false,
                // modal custo
                openCusto: false,
                custoAction: '#',
                codigoCusto: '',
                custoTotal: '',

                openModal(data) {
                    this.ordemId = data.id;
                    this.codigo = data.codigo;
                    this.selecionadosTecnicos = [...(data.tecnicos || [])];
                    this.selecionadosGestores = [...(data.gestores || [])];
                    this.equipamentoTerceiro = !!data.equip_terceiro;
                    this.atribuirTerceiros = false;
                    this.showConfirmTerceiros = false;
                    this.open = true;
                },
                closeModal() {
                    this.open = false;
                    this.atribuirTerceiros = false;
                    this.showConfirmTerceiros = false;
                },
                cancelTerceiros() {
                    this.atribuirTerceiros = false;
                    this.showConfirmTerceiros = false;
                },
                confirmTerceiros() {
                    this.showConfirmTerceiros = false;
                },
                openCustoModal(data) {
                    this.custoAction = `/ordens/${data.id}/custo`;
                    this.codigoCusto = data.codigo;
                    this.custoTotal = data.custo ?? '';
                    this.openCusto = true;
                },
                closeCustoModal() {
                    this.openCusto = false;
                },
                init() {
                    this.$watch('atribuirTerceiros', (val) => {
                        this.showConfirmTerceiros = !!val;
                    });
                }
            }));
        });
    </script>
</x-app-layout>
