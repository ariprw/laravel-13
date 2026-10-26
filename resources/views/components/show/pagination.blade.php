@if ($data->count())
    <div class="text-xs text-gray-500 italic mt-2">
        Total {{ ribuan($data->total()) }}
    </div>

    @if ($data->hasPages())
        <div class="mt-5">
            {{ $data->links() }}
        </div>
    @endif
@endif