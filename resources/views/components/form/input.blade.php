@props([
    'label',
    'name' => null,
    'id' => null,
    'type' => 'text',
    'required' => false,
    'maxlength' => 255,
    'value' => null,
    'placeholder' => null,
])

<div>
    <input
        @if ($id) id="{{ $id }}" @endif
        @if (filled($name)) name="{{ $name }}" @endif
        type="{{ $type }}"
        maxlength="{{ $maxlength }}"
        @required($required)
        @if ($type !== 'password')
            value="{{ filled($name) ? old($name, $value) : $value }}"
        @endif
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge([
        'class' => 'w-full rounded-xl border border-solid border-slate-300 focus:border-indigo-600 px-4 py-2.5 text-sm text-slate-900 outline-none',
        ]) }}
    >

    @if (filled($name))
        @error($name)
            <p class="mt-1 text-xs lg:text-sm text-red-600 italic">{{ $message }}</p>
        @enderror
    @endif
</div>