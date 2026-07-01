@php
    $alertasHorimetroCriticos = collect($alertasHorimetroCriticos ?? []);
    $alertaPrincipal = $alertasHorimetroCriticos->first();
@endphp

@if($alertaPrincipal)
    <div
        class="relative overflow-hidden rounded-2xl border border-red-300 bg-red-50 shadow-sm dark:border-red-500/50 dark:bg-red-950/35"
        x-data="{ current: 0, total: {{ $alertasHorimetroCriticos->count() }} }"
        x-init="if (total > 1) setInterval(() => current = (current + 1) % total, 4200)"
    >
        <div class="h-1.5 w-full bg-red-500"></div>
        <div class="px-5 py-4">
            <div class="relative h-[150px] overflow-hidden lg:h-[92px]">
                @foreach($alertasHorimetroCriticos as $index => $alerta)
                    <div
                        x-show="current === {{ $index }}"
                        x-transition:enter="transition-opacity duration-300 ease-out"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-200 ease-in"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @if($index > 0) style="display: none;" @endif
                        class="absolute inset-0 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-xs font-bold uppercase tracking-widest text-red-700 dark:text-red-200">
                                    Alerta de horímetro
                                </p>
                                @if($alertasHorimetroCriticos->count() > 1)
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-700 dark:bg-red-900/50 dark:text-red-200">
                                        {{ $index + 1 }}/{{ $alertasHorimetroCriticos->count() }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="mt-1 max-w-4xl truncate text-xl font-extrabold tracking-tight text-red-900 dark:text-red-100">
                                {{ $alerta['nome'] }}
                            </h3>
                            <p class="mt-1 truncate text-sm font-medium text-red-800 dark:text-red-100">
                                {{ $alerta['equipamento_nome'] ?? 'Equipamento' }}
                                @if($alerta['setor_nome'])
                                    · {{ $alerta['setor_nome'] }}
                                @endif
                            </p>
                        </div>

                        <div class="w-full max-w-[142px] shrink-0 rounded-lg border border-red-200 bg-white px-3 py-2 text-center shadow-sm dark:border-red-500/40 dark:bg-gray-900 lg:w-[142px]">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-red-600 dark:text-red-300">
                                Restante
                            </p>
                            <p class="mt-1 truncate text-xl font-black text-red-700 dark:text-red-200">
                                @if(($alerta['horas_restantes'] ?? 0) <= 0)
                                    Vencido
                                @else
                                    {{ number_format($alerta['horas_restantes'], 1, ',', '.') }} h
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($alertasHorimetroCriticos->count() > 1)
                <div class="mt-2 flex gap-1.5">
                    @foreach($alertasHorimetroCriticos as $index => $alerta)
                        <button
                            type="button"
                            class="h-1.5 rounded-full transition-all"
                            :class="current === {{ $index }} ? 'w-6 bg-red-500' : 'w-2 bg-red-300/70 dark:bg-red-700/60'"
                            @click="current = {{ $index }}"
                            aria-label="Ver alerta {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @endif

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('equipamentos.horimetros') }}"
                   class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-[11px] font-bold uppercase tracking-widest text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Ver horímetros
                </a>
            </div>
        </div>
    </div>
@endif
