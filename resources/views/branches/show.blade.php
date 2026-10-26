@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="grid gap-6">
    <x-show.card>
        <x-show.row label="ID">
            {{ $branches->id }}
        </x-show.row>

        <x-show.row label="Nama">
            {{ $branches->name }}
        </x-show.row>

        <x-show.row label="Status">
            {{ collect($status)->firstWhere('value', $branches->is_active)['label'] ?? '' }}
        </x-show.row>

        <x-show.row label="Dibuat">
            {{ $branches->created_at->format('d F Y H:i:s') }}
        </x-show.row>

        <x-show.row label="Terupdate" class="border-b-none">
            {{ $branches->updated_at->format('d F Y H:i:s') }}
        </x-show.row>
    </x-show.card>
</div>


<x-show.actions>
    <x-link-button href="{{ route('branches.index') }}">Kembali</x-link-button>

    @can('update', $branches)
        <x-link-button href="{{ route('branches.edit', $branches->id) }}" variant="primary">Edit</x-link-button>
    @endcan

    @can('delete', $branches)
        <x-link-button href="{{ route('branches.delete', $branches->id) }}" variant="danger">Hapus</x-link-button>
    @endcan
</x-show.actions>

@endsection