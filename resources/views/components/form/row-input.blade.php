@props([
    'label',
    'sublabel' => null,
    'name' => null,
    'id' => null,
    'type' => 'text',
    'required' => false,
    'maxlength' => 255,
    'value' => null,
    'placeholder' => null,
    'datasetFormat' => null,
    'datasetMaxWidth' => null,
])

<div {{ $attributes->class([
    'grid grid-cols-10 gap-4 items-start',
    'lg:items-center',
]) }}>
    <div class="col-span-3">
        <div class="text-xs lg:text-sm font-bold">
            {{ $label }}
        </div>

        @if($sublabel)
            <div class="text-xs text-gray-500">
                {{ $sublabel }}
            </div>
        @endif
    </div>
    
    <div class="col-span-7">
        <input
            @if ($id) id="{{ $id }}" @endif
            @if (filled($name)) name="{{ $name }}" @endif
            type="{{ $type }}"
            maxlength="{{ $maxlength }}"
            @if ($datasetFormat) data-format="{{ $datasetFormat }}" @endif
            @if ($datasetMaxWidth) data-max-width="{{ $datasetMaxWidth }}" @endif
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
</div>