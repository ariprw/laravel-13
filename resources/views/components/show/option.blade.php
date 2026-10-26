@props([
    'detail' => null,
    'edit' => null,
    'delete' => null,
])

@if ($detail)
    <x-link-option href="{{ $detail }}">
        {!! ic('info', 'w-5 h-5') !!}
    </x-link-option>
@endif

@if ($edit)
    <x-link-option href="{{ $edit }}">
        {!! ic('edit', 'w-5 h-5') !!}
    </x-link-option>
@endif

@if ($delete)
    <x-link-option href="{{ $delete }}">
        {!! ic('delete', 'w-5 h-5') !!}
    </x-link-option>
@endif