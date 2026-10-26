<div {{ $attributes->class([
    'grid grid-cols-10 gap-4 items-start border-b border-b-solid border-gray-200 mb-3 pb-3',
    'lg:items-center',
]) }}>
    <div class="col-span-3 text-xs lg:text-sm text-green-600 font-bold">
        {{ $label }}
    </div>

    <div class="col-span-7 text-sm">
        {{ $slot }}
    </div>
</div>