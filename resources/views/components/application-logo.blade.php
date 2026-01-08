@php
    $logoClasses = trim('block ' . ($attributes->get('class') ?? ''));
@endphp

<img src="{{ asset('imagem/Logo-AgroWerk.svg') }}"
     {{ $attributes->merge(['class' => $logoClasses . ' dark:hidden']) }}>
<img src="{{ asset('imagem/Logo_AgroWerk_white.png') }}"
     {{ $attributes->merge(['class' => $logoClasses . ' hidden dark:block']) }}>
