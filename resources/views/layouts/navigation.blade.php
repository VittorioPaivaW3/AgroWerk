<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-verdes-verde_claro/20 dark:border-verdes-verde_claro/30 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                @php
                    $homeRoute = route('dashboard');
                    if(auth()->check() && auth()->user()->hasRole('visualizador')) {
                        $homeRoute = route('dashboard.visualizador');
                    } elseif(auth()->check() && auth()->user()->hasRole('tecnico')) {
                        $homeRoute = route('tecnico.dashboard');
                    }
                @endphp

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $homeRoute }}">
                        <x-application-logo class="block h-12 w-auto fill-current text-verdes-verde_escuro dark:text-verdes-verde_claro" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex sm:items-center">

                    @unlessrole('visualizador')
                        {{-- Dropdown Cadastro --}}
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent
                                           text-sm font-medium leading-5
                                           text-gray-500 dark:text-gray-400
                                           hover:text-verdes-verde_folha dark:hover:text-verdes-verde_claro
                                           hover:border-verdes-verde_claro/60 dark:hover:border-verdes-verde_claro/40
                                           focus:outline-none focus:text-verdes-verde_folha dark:focus:text-verdes-verde_claro
                                           focus:border-verdes-verde_claro/60 dark:focus:border-verdes-verde_claro/40
                                           transition duration-150 ease-in-out"
                                >
                                    Cadastro

                                    <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @unlessrole('tecnico')
                                    <x-dropdown-link :href="route('usuarios.index')">
                                        {{ __('Usuários') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('setores.index')">
                                        {{ __('Setores') }}
                                    </x-dropdown-link>
                                @endunlessrole

                                <x-dropdown-link :href="route('equipamentos.index')">
                                    {{ __('Equipamentos') }}
                                </x-dropdown-link>

                                {{-- <x-dropdown-link :href="route('tecnicos.index')">
                                    {{ __('Técnicos') }}
                                </x-dropdown-link> --}}
                            </x-slot>
                        </x-dropdown>
                    @endunlessrole

                    {{-- Links padrão (admin/gestor) --}}
                    @role('admin|gestor')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('ordens.index')" :active="request()->routeIs('ordens.*')">
                            Ordens de Serviço
                        </x-nav-link>

                        <x-nav-link :href="route('projetos.index')" :active="request()->routeIs('projetos.*')">
                            {{ __('Projetos') }}
                        </x-nav-link>

                        <x-nav-link :href="route('relatorios.index')" :active="request()->routeIs('relatorios.*')">
                            {{ __('Relatórios') }}
                        </x-nav-link>
                    @endrole

                    {{-- Painel do visualizador --}}
                    @role('visualizador')
                        <x-nav-link :href="route('dashboard.visualizador')" :active="request()->routeIs('dashboard.visualizador')">
                            Meu Painel
                        </x-nav-link>

                        <x-nav-link :href="route('ordens.create')" :active="request()->routeIs('ordens.create')">
                            Abrir OS
                        </x-nav-link>
                    @endrole

                    @auth
                        @if(auth()->user()->hasRole('tecnico'))
                            <x-nav-link :href="route('tecnico.dashboard')" :active="request()->routeIs('tecnico.dashboard')">
                                {{ __('Painel do Técnico') }}
                            </x-nav-link>
                        @endif
                    @endauth

                    @unlessrole('visualizador')
                        <x-nav-link :href="route('manutencoes.preventivas.index')" :active="request()->routeIs('manutencoes.preventivas.*')">
                             {{ __('Manutenção preventiva') }}
                        </x-nav-link>
                    @endunlessrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <button
                    x-data
                    @click="
                        const html = document.documentElement;

                        if (html.classList.contains('dark')) {
                            html.classList.remove('dark');
                            localStorage.setItem('theme', 'light');
                        } else {
                            html.classList.add('dark');
                            localStorage.setItem('theme', 'dark');
                        }
                      "      
                id="theme-toggle" type="button"
                    class="text-gray-500 dark:text-gray-300 hover:bg-verdes-verde_claro/10 dark:hover:bg-verdes-verde_claro/10 focus:outline-none focus:ring-4 focus:ring-verdes-verde_claro/30 dark:focus:ring-verdes-verde_claro/40 rounded-lg text-sm p-2.5">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md
                                   text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800
                                   hover:text-verdes-verde_folha dark:hover:text-verdes-verde_claro
                                   focus:outline-none transition ease-in-out duration-150"
                        >
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                      this.closest('form').submit();">
                                {{ __('Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md
                               text-gray-400 dark:text-gray-500
                               hover:text-gray-500 dark:hover:text-gray-400
                               hover:bg-verdes-verde_claro/10 dark:hover:bg-verdes-verde_claro/10
                               focus:outline-none focus:bg-verdes-verde_claro/10 dark:focus:bg-verdes-verde_claro/10
                               focus:text-gray-500 dark:focus:text-gray-400
                               transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @role('visualizador')
                <x-responsive-nav-link :href="route('dashboard.visualizador')" :active="request()->routeIs('dashboard.visualizador')">
                    {{ __('Meu Painel') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ordens.create')" :active="request()->routeIs('ordens.create')">
                    {{ __('Abrir OS') }}
                </x-responsive-nav-link>
            @elserole('admin|gestor')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ordens.index')" :active="request()->routeIs('ordens.*')">
                    {{ __('Ordens') }}
                </x-responsive-nav-link>
            @elserole('tecnico')
                <x-responsive-nav-link :href="route('tecnico.dashboard')" :active="request()->routeIs('tecnico.dashboard')">
                    {{ __('Painel do Tecnico') }}
                </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-verdes-verde_claro/30 dark:border-verdes-verde_claro/30">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
