<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Usuário') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-1 border-b border-gray-100 dark:border-white/10 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Cadastro do usuário
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Defina os dados de acesso e o perfil.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('usuarios.store') }}" class="mt-6 space-y-6">
                        @csrf

                        {{-- Nome --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome
                            </label>
                            <input id="name" name="name" type="text" required
                                   value="{{ old('name') }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- E-mail --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                E-mail
                            </label>
                            <input id="email" name="email" type="email" required
                                   value="{{ old('email') }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Senha --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Senha
                                </label>
                                <input id="password" name="password" type="password" required
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                              text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Confirmar senha
                                </label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                              text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                            </div>
                        </div>

                        {{-- Perfil --}}
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Perfil (papel)
                            </label>
                            <select id="role" name="role" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-verdes-verde_claro focus:ring-verdes-verde_claro">
                                <option value="">Selecione um perfil</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                    {{-- Valor da hora (só faz sentido pra técnico) --}}
                    <div x-show="role === 'tecnico'" x-cloak class="rounded-lg border border-verdes-verde_claro/20 bg-verdes-verde_claro/5 p-3 dark:border-verdes-verde_claro/30 dark:bg-verdes-verde_claro/10">
                        <x-input-label for="valor_hora" value="Valor da hora (R$)" />
                        <x-text-input
                            id="valor_hora"
                            name="valor_hora"
                            type="number"
                            step="0.01"
                            min="0"
                            class="mt-1 block w-full focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                            :value="old('valor_hora')"   {{-- <-- apenas old(), nada de $us / $usuario --}}
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            Use ponto como separador decimal (ex: 75.50).
                        </p>
                        <x-input-error :messages="$errors->get('valor_hora')" class="mt-2" />
                    </div>

                        {{-- Botões --}}
                        <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                            <a href="{{ route('usuarios.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md
                                      text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-widest
                                      hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </a>

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
        </div>
    </div>
</x-app-layout>
