@props([
    'href' => '',
    'variant' => 'secondary',
])

@php
$variants = [
    'primary' => 'bg-lime-600 text-white hover:bg-black',
    'secondary' => 'bg-white text-lime-700 border border-solid border-lime-600 hover:bg-slate-100',
    'danger' => 'bg-red-600 text-white hover:bg-black',
];
@endphp

<a href="{{ $href }}" {{ $attributes->merge([
    'class'=>'inline-block rounded-xl px-4 py-2.5 text-sm font-medium text-center select-none '.($variants[$variant] ?? $variants['secondary'])
    ]) }}>{{ $slot }}</a>