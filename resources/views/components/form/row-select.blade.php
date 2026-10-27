@props([
    'label',
    'name',
    'id' => null,
    'required' => false,
    'options' => [],
    'value' => null,
    'placeholder' => 'Pilih...',
])

@php
    $selectedValue = old($name, $value);
@endphp

<div {{ $attributes->class([
    'grid grid-cols-10 gap-4 items-start',
    'lg:items-center',
]) }}>
    <div class="col-span-3 text-xs lg:text-sm font-bold">
        {{ $label }}
    </div>

    <div class="col-span-7">
        <div class="customSelect">
            <select
                @if ($id) id="{{ $id }}" @endif
                name="{{ $name }}"
                @required($required)
                {{ $attributes->merge([
                    'class' => '',
                ]) }}
            >
                <option
                    value=""
                    @if ($required) disabled @endif
                    @selected($selectedValue === null || $selectedValue === '')
                >
                    {{ $placeholder }}
                </option>

                @foreach ($options as $key => $option)
                    @php
                        $optionSubLabel1 = null;
                        $optionSubLabel2 = null;

                        if (is_array($option)) {
                            if (array_key_exists('value', $option) && array_key_exists('label', $option)) {
                                $optionValue = $option['value'];
                                $optionLabel = $option['label'];
                            } else {
                                $optionValue = $key;
                                $optionLabel = $option[0] ?? '';

                                $optionSubLabel1 = $option[1] ? '<div class="text-xs text-gray-500 italic">'.$option[1].'</div>' : null;
                                $optionSubLabel2 = $option[2] ? '<div class="text-xs text-gray-500 italic">'.$option[2].'</div>' : null;
                            }
                        } else {
                            $optionValue = is_int($key) ? $option : $key;
                            $optionLabel = $option;
                        }
                    @endphp

                    <option
                        value="{{ $optionValue }}"
                        @selected((string) $selectedValue === (string) $optionValue)
                    >{{ ucfirst($optionLabel) }}{!! $optionSubLabel1.$optionSubLabel2 !!}</option>
                @endforeach
            </select>
        </div>

        @error($name)
            <p class="mt-1 text-xs lg:text-sm text-red-600 italic">{{ $message }}</p>
        @enderror
    </div>
</div>
