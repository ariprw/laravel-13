@props([
    'href' => '',
    'variant' => 'secondary',
])

@php
$variants = [
    'primary' => 'bg-indigo-600 text-white hover:bg-black',
    'secondary' => 'bg-white text-indigo-700 border border-solid border-indigo-600 hover:bg-slate-100',
    'danger' => 'bg-red-600 text-white hover:bg-black',
];
@endphp

<a href="{{ $href }}" {{ $attributes->merge([
    'class'=>'inline-block rounded-xl px-4 py-2.5 text-sm font-medium text-center select-none '.($variants[$variant] ?? $variants['secondary'])
    ]) }}>{{ $slot }}</a>