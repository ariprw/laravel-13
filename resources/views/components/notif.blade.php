@props([
    'type' => 'success',
])

@php
    $variants = [
        'success' => 'bg-green-100 text-green-800',
        'error' => 'bg-red-100 text-red-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'info' => 'bg-blue-100 text-blue-800',
    ];
@endphp

<div {{ $attributes->class([
    'mb-5 p-4 rounded-xl',
    ($variants[$type] ?? $variants['info']),
]) }}>
    <p class="text-sm lg:text-base">
        {{ $slot }}
    </p>
</div>