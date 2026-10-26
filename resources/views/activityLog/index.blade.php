@extends('layouts.app')

@section('title', $title)

@section('content')

@php
    $columns = [
        'No',
        'Tanggal',
        'Deskripsi',
        'Opsi',
    ];

    $spans = [1, 3, 4, 2];
@endphp

<x-show.card>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-5">
        <div class="flex justify-start">
            @if (request()->hasAny(['search']))
                <div>
                    <x-link-button href="{{ route('activity.log.index') }}">Kembali</x-link-button>
                </div>
            @endif
        </div>

        <div>
            <form action="{{ route('activity.log.index') }}" method="GET">
                <x-form.input name="search" placeholder="Cari Nama / Deskripsi" value="{{ request('search') }}" />
            </form>
        </div>
    </div>

    <div class="hidden lg:grid grid-cols-10 items-center border border-b-solid border-gray-200">
        @foreach ($columns as $index => $column)
            <div class="col-span-{{ $spans[$index] }} text-sm font-bold p-2">
                {{ $column }}
            </div>
        @endforeach
    </div>

    @forelse ($activityLog as $item)
        <div class="grid grid-cols-1 lg:grid-cols-10 items-center border border-t-solid border-r-solid border-b-solid border-l-solid lg:border-t-none lg:border-r-none lg:border-l-none border-gray-200 rounded-xl lg:rounded-none mb-5 lg:mb-0">
            @php
                $no = $activityLog->firstItem() + $loop->index;
            @endphp

            @foreach ($columns as $index => $column)
                <div class="lg:col-span-{{ $spans[$index] }} grid grid-cols-10 lg:grid-cols-2 text-sm p-2 {{ ($loop->last) ? '' : 'border border-b-solid lg:border-b-none border-gray-200' }}">
                    <span class="lg:hidden col-span-4 font-bold">
                        {{ $column }}
                    </span>

                    @switch($index)
                        @case(0)
                            <span class="col-span-6">
                                {{ $no }}
                            </span>
                            @break

                        @case(1)
                            <span class="col-span-6">
                                <div>{{ $item->created_at->format('d F Y H:i:s') }}</div>
                                <div class="font-medium text-xs text-lime-700 italic">{{ $item->user?->name ?? $item->user_id }}</div>
                                <div class="font-medium text-xs text-gray-500 italic">{{ $item->user?->branches?->name ?? '' }}</div>
                            </span>
                            @break

                        @case(2)
                            <span class="col-span-6">
                                {{ $item->description }}
                            </span>
                            @break

                        @case(3)
                            <span class="col-span-6">
                                <x-show.option
                                    :detail="route('activity.log.show', $item->id)"
                                />
                            </span>
                            @break
                    @endswitch
                </div>
            @endforeach
        </div>
    @empty
        <div class="p-2 text-sm italic">Belum ada data</div>
    @endforelse

    <x-show.pagination :data="$activityLog" />
</x-show.card>

{!! customSelect() !!}

@endsection