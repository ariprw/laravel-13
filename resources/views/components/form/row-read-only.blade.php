<div {{ $attributes->class([
    'grid grid-cols-10 gap-4 items-start',
    'lg:items-center',
]) }}>
    <div class="col-span-3 text-xs lg:text-sm font-bold">
        {{ $label }}
    </div>
    
    <div class="col-span-7">
        <div {{ $attributes->except('class')->merge([
                'class' => 'inline-block bg-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 select-none',
            ]) }}>{{ $slot }}</div>
    </div>
</div>