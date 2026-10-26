@props([
    'label',
    'name',
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
        name="{{ $name }}"
        type="{{ $type }}"
        maxlength="{{ $maxlength }}"
        @required($required)
        @if ($type !== 'password')
        value="{{ old($name, $value) }}"
        @endif
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->except('class')->merge([
        'class' => 'w-full rounded-xl border border-solid border-slate-300 focus:border-lime-600 px-4 py-2.5 text-sm text-slate-900 outline-none',
        ]) }}
    >

    @error($name)
        <p class="mt-1 text-xs lg:text-sm text-red-600 italic">{{ $message }}</p>
    @enderror
</div>