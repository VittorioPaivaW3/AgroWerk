@props([
    'showLogo' => true,
    'bodyClass' => 'font-sans text-gray-900 antialiased',
    'containerClass' => 'min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900',
    'containerStyle' => '',
    'containerOverlayClass' => '',
    'containerOverlayStyle' => '',
    'cardClass' => 'w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('imagem/Logo-AgroWerk.svg') }}" media="(prefers-color-scheme: light)">
        <link rel="icon" type="image/png" href="{{ asset('imagem/Logo_AgroWerk_white.png') }}" media="(prefers-color-scheme: dark)">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="{{ $bodyClass }}">
        <div class="{{ $containerClass }} relative" style="{{ $containerStyle }}">
            @if($containerOverlayClass || $containerOverlayStyle)
                <div class="absolute inset-0 pointer-events-none {{ $containerOverlayClass }}" style="{{ $containerOverlayStyle }}"></div>
            @endif

            <div class="relative z-10 w-full flex flex-col items-center">
                @if($showLogo)
                    <div>
                        <a href="/">
                            <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                        </a>
                    </div>
                @endif

                <div class="{{ $cardClass }}">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
