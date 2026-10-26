<div {{ $attributes->class([
    'bg-white rounded-xl border border-solid border-gray-200 p-3 lg:p-6',
]) }}>
    @if (isset($title))
        <div class="border-b border-b-solid border-gray-200 mb-3 pb-3">
            <div class="text-base font-bold">{{ $title }}</div>

            @if (isset($subtitle))
                <div class="text-sm text-gray-500">{{ $subtitle }}</div>
            @endif
        </div>
    @endif

    @if (session('status'))
        <x-notif type="success">
            {{ session('status') }}
        </x-notif>
    @endif

    @if (session('error'))
        <x-notif type="error">
            {{ session('error') }}
        </x-notif>
    @endif

    <div>
        {{ $slot }}
    </div>
</div>