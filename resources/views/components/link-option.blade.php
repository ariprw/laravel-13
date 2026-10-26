@props([
    'href' => '',
    'variant' => 'primary',
])

@php
$variants = [
    'primary' => 'text-lime-700 hover:text-black',
    'danger' => 'text-red-600 hover:text-black',
];
@endphp

<a href="{{ $href }}" {{ $attributes->merge([
    'class'=>'inline-block m-1 select-none '.($variants[$variant] ?? $variants['primary'])
    ]) }}>{{ $slot }}</a>