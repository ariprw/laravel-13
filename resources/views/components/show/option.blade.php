@props([
    'detail' => null,
    'edit' => null,
    'delete' => null,
])

@if ($detail)
    <x-link-option href="{{ $detail }}">
        <i data-lucide="info" class="w-5 h-5"></i>
    </x-link-option>
@endif

@if ($edit)
    <x-link-option href="{{ $edit }}">
        <i data-lucide="edit" class="w-5 h-5"></i>
    </x-link-option>
@endif

@if ($delete)
    <x-link-option href="{{ $delete }}">
        <i data-lucide="trash" class="w-5 h-5"></i>
    </x-link-option>
@endif