@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-verdes-verde_claro dark:border-verdes-verde_folha text-start text-base font-medium text-verdes-verde_folha dark:text-verdes-verde_claro bg-verdes-verde_claro/15 dark:bg-verdes-verde_claro/20 focus:outline-none focus:text-verdes-verde_folha dark:focus:text-verdes-verde_claro focus:bg-verdes-verde_claro/20 dark:focus:bg-verdes-verde_claro/20 focus:border-verdes-verde_folha dark:focus:border-verdes-verde_folha transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-verdes-verde_folha dark:hover:text-verdes-verde_claro hover:bg-verdes-verde_claro/10 dark:hover:bg-verdes-verde_claro/10 hover:border-verdes-verde_claro/30 dark:hover:border-verdes-verde_claro/30 focus:outline-none focus:text-verdes-verde_folha dark:focus:text-verdes-verde_claro focus:bg-verdes-verde_claro/10 dark:focus:bg-verdes-verde_claro/10 focus:border-verdes-verde_claro/30 dark:focus:border-verdes-verde_claro/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
