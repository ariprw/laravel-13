<div>
    <div {{ $attributes->merge([
        'class' => 'inline-block bg-slate-200 rounded-xl px-3 py-1.5 text-sm text-slate-900 select-none',
        ]) }}>{{ $slot }}</div>
</div>