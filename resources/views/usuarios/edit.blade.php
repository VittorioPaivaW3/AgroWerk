<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Usuário') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-6 text-gray-900 dark:text-gray-100">

                    <form method="POST"
                          action="{{ route('usuarios.update', $usuario) }}"
                          class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Nome --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome
                            </label>
                            <input id="name" name="name" type="text" required
                                   value="{{ old('name', $usuario->name) }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
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
                                   value="{{ old('email', $usuario->email) }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Senha (opcional) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nova senha (opcional)
                                </label>
                                <input id="password" name="password" type="password"
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                              text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Confirmar nova senha
                                </label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                              text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        {{-- Perfil --}}
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Perfil (papel)
                            </label>
                            <select id="role" name="role" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecione um perfil</option>
                                @php
                                    $currentRole = $usuario->roles->first()->name ?? null;
                                @endphp
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        @selected(old('role', $currentRole) === $role->name)>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
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
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md
                                           text-xs font-semibold text-white uppercase tracking-widest
                                           hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Salvar alterações
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
