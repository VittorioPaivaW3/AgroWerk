@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-verdes-verde_claro dark:border-verdes-verde_folha text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-verdes-verde_folha transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-verdes-verde_folha dark:hover:text-verdes-verde_claro hover:border-verdes-verde_claro/60 dark:hover:border-verdes-verde_claro/40 focus:outline-none focus:text-verdes-verde_folha dark:focus:text-verdes-verde_claro focus:border-verdes-verde_claro/60 dark:focus:border-verdes-verde_claro/40 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
