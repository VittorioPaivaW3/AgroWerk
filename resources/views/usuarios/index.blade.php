<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Topo --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Lista de usuários
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Gerencie os usuários e seus perfis de acesso.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('usuarios.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-verdes-verde_claro border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest
                                  hover:bg-verdes-verde_folha focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-verdes-verde_claro">
                            Novo Usuário
                        </a>
                    </div>
                </div>
            </div>
            {{-- Alertas --}}
            @if (session('success'))
                <div class="bg-verdes-verde_claro/15 border border-verdes-verde_claro/30 text-verdes-verde_escuro text-sm px-4 py-2 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-200 text-red-800 text-sm px-4 py-2 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tabela --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="h-1.5 w-full bg-verdes-verde_claro"></div>
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-verdes-verde_claro/10 dark:bg-verdes-verde_claro/10">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nome
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                E-mail
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Perfil
                            </th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($usuarios as $usuario)
                            <tr class="hover:bg-verdes-verde_claro/5 dark:hover:bg-verdes-verde_claro/10 transition">
                                <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $usuario->name }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $usuario->email }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $usuario->roles->pluck('name')->implode(', ') ?: '—' }}
                                </td>
                                <td class="px-3 py-2 text-sm text-right space-x-2">
                                    <a href="{{ route('usuarios.edit', $usuario) }}"
                                       class="text-verdes-verde_claro hover:text-verdes-verde_folha dark:text-verdes-verde_claro dark:hover:text-verdes-verde_folha text-xs font-semibold">
                                        Editar
                                    </a>

                                    @if(auth()->id() !== $usuario->id)
                                        <form action="{{ route('usuarios.destroy', $usuario) }}"
                                              method="POST"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?')"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-semibold">
                                                Excluir
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $usuarios->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
