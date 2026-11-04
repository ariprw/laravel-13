@props([
    'label',
    'sublabel' => null,
    'href' => null,
    'target' => null,
    'src',
    'alt' => '',
])

<div {{ $attributes->class([
    'grid grid-cols-10 gap-4 items-start border-b border-b-solid border-gray-200 mb-3 pb-3',
    'lg:items-center',
]) }}>
    <div class="col-span-3">
        <div class="text-xs lg:text-sm text-indigo-600 font-bold">
            {{ $label }}
        </div>

        @if($sublabel)
            <div class="text-xs text-gray-500">
                {{ $sublabel }}
            </div>
        @endif
    </div>

    <div class="col-span-7">
        @if($href)
            <a
                href="{{ $href }}"
                @if($target) target="{{ $target }}" @endif
                @if($target === '_blank') rel="noopener noreferrer" @endif
            >
                <img 
                    src="{{ $src }}"
                    alt="{{ $alt }}"
                    {{ $attributes->except(['id'])->merge([
                    'class' => 'inline-block w-48 h-48 object-cover border border-solid border-gray-200 rounded-xl select-none',
                    ]) }}
                >
            </a>
        @else
            <img 
                src="{{ $src }}"
                alt="{{ $alt }}"
                {{ $attributes->except(['id'])->merge([
                'class' => 'inline-block w-48 h-48 object-cover border border-solid border-gray-200 rounded-xl select-none',
                ]) }}
            >
        @endif
    </div>
</div>