@extends('layouts.app')

@section('title', $title)

@section('content')

@php
    $columns = [
        'No',
        'Nama Lengkap',
        'Email',
        'Status',
        'Role',
        'Opsi',
    ];

    $spans = [1, 2, 2, 2, 2, 2];
@endphp

<x-show.card>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-5">
        <div class="flex justify-start">
            @if (request()->hasAny(['search', 'status', 'role']))
                <div>
                    <x-link-button href="{{ route('user.index') }}">Kembali</x-link-button>
                </div>
            @endif
        </div>

        <div>
            <form action="{{ route('user.index') }}" method="GET">
                <x-form.input name="search" placeholder="Cari Nama / Email" value="{{ request('search') }}" />

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

                    <div class="col-span-4">
                        <x-form.select
                        label="Role"
                        name="role"
                        :options="$role"
                        :value="request('role')"
                        placeholder="Semua Role"
                        />
                    </div>

                    <div class="col-span-3">
                        <x-button type="submit">Cari</x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="hidden lg:grid grid-cols-11 items-center border border-b-solid border-gray-200">
        @foreach ($columns as $index => $column)
            <div class="col-span-{{ $spans[$index] }} text-sm font-bold p-2">
                {{ $column }}
            </div>
        @endforeach
    </div>

    @forelse ($user as $item)
        <div class="grid grid-cols-1 lg:grid-cols-11 items-center border border-t-solid border-r-solid border-b-solid border-l-solid lg:border-t-none lg:border-r-none lg:border-l-none border-gray-200 rounded-xl lg:rounded-none mb-5 lg:mb-0">
            @php
                $no = $user->firstItem() + $loop->index;
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
                                {{ $item->email }}
                            </span>
                            @break

                        @case(3)
                            <span class="col-span-6">
                                {{ ucfirst($item->status) }}
                            </span>
                            @break

                        @case(4)
                            <span class="col-span-6">
                                <div>
                                    <div class="inline-block bg-slate-900 text-white text-xs italic px-2 rounded-xl">{{ ucfirst($item->role) }}</div>
                                </div>

                                @if ($item->branches)
                                    <div>
                                        <div class="inline-block border border-solid border-slate-800 text-slate-800 text-xs italic mt-1 px-2 rounded-xl">{{ $item->branches?->name ?? $item->branch_id }}</div>
                                    </div>
                                @endif
                            </span>
                            @break

                        @case(5)
                            <span class="col-span-6">
                                @can('delete', $item)
                                    <x-show.option
                                        :detail="route('user.show', $item->id)"
                                        :edit="route('user.edit', $item->id)"
                                        :delete="route('user.delete', $item->id)"
                                    />
                                @else
                                    <x-show.option
                                        :detail="route('user.show', $item->id)"
                                        :edit="route('user.edit', $item->id)"
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

    <x-show.pagination :data="$user" />
</x-show.card>

{!! customSelect() !!}

@endsection