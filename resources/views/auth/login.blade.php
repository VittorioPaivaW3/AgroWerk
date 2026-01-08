@php
    $loginBackground = asset('imagem/fundo_verdeC.jpg');
@endphp

<x-guest-layout
    :showLogo="false"
    body-class="font-sans text-gray-900 antialiased"
    containerClass="min-h-screen flex items-center justify-center bg-gradient-to-br from-verdes-verde_folha via-white to-verdes-verde_folha/10 px-4 py-10"
    :containerStyle="'background-image: url(' . $loginBackground . '); background-size: cover; background-position: center; background-repeat: no-repeat;'"
    containerOverlayClass="backdrop-blur-md"
    cardClass="w-full max-w-5xl p-0 bg-transparent shadow-none">
    <div class="relative w-full">
        <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_1fr] lg:min-h-[520px] overflow-hidden rounded-3xl">
            <div class="relative hidden lg:flex flex-col items-center justify-center p-10 text-white text-center login-fade">
                <div class="absolute inset-0">
                    <img src="{{ asset('imagem/Fundo_verde.jpg') }}" alt="" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-verdes-verde_escuro/70"></div>
                    <div class="absolute inset-0 opacity-70 mix-blend-soft-light"
                         style="background-image: radial-gradient(circle at 20% 20%, rgba(141,198,63,0.35), transparent 55%),
                                radial-gradient(circle at 80% 10%, rgba(99,190,21,0.3), transparent 45%),
                                linear-gradient(115deg, rgba(0,56,27,0.35), rgba(0,132,61,0.2));"></div>
                </div>

                <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-verdes-verde_claro/25 blur-2xl"></div>
                <div class="absolute bottom-10 left-10 h-20 w-20 rounded-full border border-white/25"></div>
                <div class="absolute top-20 left-14 h-12 w-12 rounded-lg border border-white/20 rotate-12"></div>

                <div class="relative z-10 flex flex-col items-center gap-4">
                    <img src="{{ asset('imagem/Logo_AgroWerk_white.png') }}" alt="AgroWerk" class="h-24 w-auto">

                    <div class="max-w-xs space-y-2">
                        <h2 class="text-lg font-semibold">Bem-vindo de volta!</h2>
                        <p class="text-xs text-white/80">
                            Entre para acessar sua conta e acompanhar as manutencoes.
                        </p>
                    </div>


                 
                </div>
            </div>

            <div class="relative p-8 sm:p-10 lg:p-12 login-fade login-fade-delay bg-[url('/imagem/Fundo_verde.jpg')] bg-cover bg-center bg-no-repeat">
                <div class="absolute inset-0 bg-white/85 backdrop-blur-2xl"></div>
                <div class="relative">
                    <div class="mb-6">
                        <div class="mb-4 flex items-center gap-3 lg:hidden">
                            <img src="{{ asset('imagem/Logo-AgroWerk.svg') }}" alt="AgroWerk" class="h-10 w-auto">
                            <span class="text-xs font-semibold text-verdes-verde_folha uppercase tracking-widest">AgroWerk</span>
                        </div>
                        <h1 class="text-2xl font-semibold text-gray-900">Entrar</h1>
                        <p class="mt-1 text-sm text-gray-500">Use seu e-mail e senha para continuar.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('E-mail')" class="text-xs font-semibold text-gray-600 uppercase tracking-widest" />
                            <div class="relative mt-2">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18M3 6l9 7 9-7M3 6v12h18V6"/>
                                    </svg>
                                </span>
                                <x-text-input id="email"
                                              class="block w-full rounded-lg border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                              type="email"
                                              name="email"
                                              :value="old('email')"
                                              required
                                              autofocus
                                              autocomplete="username"
                                              placeholder="seu@email.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Senha')" class="text-xs font-semibold text-gray-600 uppercase tracking-widest" />
                            <div class="relative mt-2">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="10" rx="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                </span>
                                <x-text-input id="password"
                                              class="block w-full rounded-lg border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm focus:border-verdes-verde_claro focus:ring-verdes-verde_claro"
                                              type="password"
                                              name="password"
                                              required
                                              autocomplete="current-password"
                                              placeholder="Sua senha" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                            <label for="remember_me" class="inline-flex items-center gap-2 text-gray-600">
                                <input id="remember_me" type="checkbox"
                                       class="rounded border-gray-300 text-verdes-verde_claro shadow-sm focus:ring-verdes-verde_claro"
                                       name="remember">
                                Lembrar de mim
                            </label>

                            @if (Route::has('password.request'))
                                <a class="font-semibold text-verdes-verde_claro hover:text-verdes-verde_folha transition" href="{{ route('password.request') }}">
                                    Esqueceu sua senha?
                                </a>
                            @endif
                        </div>

                        <x-primary-button class="w-full justify-center rounded-lg bg-verdes-verde_claro hover:bg-verdes-verde_folha text-white text-sm normal-case tracking-normal py-2.5 focus:ring-verdes-verde_claro focus:ring-offset-0">
                            Entrar
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .login-fade { animation: loginFadeUp .6s ease both; }
        .login-fade-delay { animation-delay: .08s; }

        @keyframes loginFadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-guest-layout>
