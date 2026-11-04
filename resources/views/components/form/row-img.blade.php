@props([
    'label',
    'sublabel' => null,
    'href' => null,
    'target' => null,
    'src',
    'alt' => '',
    'remove' => null,
    'name' => null,
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
                        'class' => 'inline-block w-48 h-48 object-cover border border-solid border-slate-300 rounded-xl select-none',
                    ]) }}
                >
            </a>
        @else
            <img 
                src="{{ $src }}"
                alt="{{ $alt }}"
                {{ $attributes->except(['id'])->merge([
                    'class' => 'inline-block w-48 h-48 object-cover border border-solid border-slate-300 rounded-xl select-none',
                ]) }}
            >
        @endif

        @if($remove)
            <div class="mt-2">
                <label class="bg-red-600 hover:bg-black text-white rounded-xl select-none cursor-pointer px-2 py-1 inline-flex items-center gap-1">
                    <input
                        type="checkbox"
                        @if($name)
                            name="{{ $name }}"
                        @endif
                        value="1"
                    />
                    <span class="text-xs">Hapus</span>
                </label>
            </div>
        @endif
    </div>
</div>