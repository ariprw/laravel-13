@extends('layouts.app')

@section('title', $title)

@section('content')

@php
    $columns = [
        'No',
        'Nama',
        'Jumlah User',
        'Status',
        'Opsi',
    ];

    $spans = [1, 3, 2, 2, 2];
@endphp

<x-show.card>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-5">
        <div class="flex justify-start">
            @if (request()->hasAny(['search', 'status']))
                <div>
                    <x-link-button href="{{ route('branches.index') }}">Kembali</x-link-button>
                </div>
            @else
                <div>
                    <x-link-button href="{{ route('branches.create') }}" variant="primary">Buat</x-link-button>
                </div>
            @endif
        </div>

        <div>
            <form action="{{ route('branches.index') }}" method="GET">
                <x-form.input name="search" placeholder="Cari Nama" value="{{ request('search') }}" />

                <div class="grid grid-cols-11 gap-1 items-center">
                    <div class="col-span-4">
                        <x-form.select
                        label="Status"
                        name="status"
                        :options="$status"
                        :value="request('status')"
                        placeholder="Semua Status"
                        />
                    </div>

                    <div class="col-span-3">
                        <x-button type="submit">Cari</x-button>
                    </div>
                </div>
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

    @forelse ($branches as $item)
        <div class="grid grid-cols-1 lg:grid-cols-10 items-center border border-t-solid border-r-solid border-b-solid border-l-solid lg:border-t-none lg:border-r-none lg:border-l-none border-gray-200 rounded-xl lg:rounded-none mb-5 lg:mb-0">
            @php
                $no = $branches->firstItem() + $loop->index;
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
                                {{ $item->name }}
                            </span>
                            @break

                        @case(2)
                            <span class="col-span-6">
                                {{ ribuan($item->users_count) }}
                            </span>
                            @break

                        @case(3)
                            <span class="col-span-6">
                                {{ collect($status)->firstWhere('value', $item->is_active)['label'] ?? '' }}
                            </span>
                            @break

                        @case(4)
                            <span class="col-span-6">
                                @can('delete', $item)
                                    <x-show.option
                                        :detail="route('branches.show', $item->id)"
                                        :edit="route('branches.edit', $item->id)"
                                        :delete="route('branches.delete', $item->id)"
                                    />
                                @else
                                    <x-show.option
                                        :detail="route('branches.show', $item->id)"
                                        :edit="route('branches.edit', $item->id)"
                                    />
                                @endcan
                            </span>
                            @break
                    @endswitch
                </div>
            @endforeach
        </div>
    @empty
        <div class="p-2 text-sm italic">Belum ada data</div>
    @endforelse

    <x-show.pagination :data="$branches" />
</x-show.card>

{!! customSelect() !!}

@endsection