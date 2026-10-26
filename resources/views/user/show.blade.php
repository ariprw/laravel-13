@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="grid gap-6">
    <x-show.card>
        <x-slot:title>
            Personal
        </x-slot:title>

        <x-show.row label="Nama Lengkap">
            {{ $user->name }}
        </x-show.row>

        <x-show.row label="Email" class="border-b-none">
            {{ $user->email }}
        </x-show.row>
    </x-show.card>
    

    <x-show.card>
        <x-slot:title>
            Akun
        </x-slot:title>
        
        <x-show.row label="Email Diverifikasi">
            @if ($user->email_verified_at)
                {{ $user->email_verified_at->format('d F Y H:i:s') }}
            @else
                <span class="text-red-500">Belum Terverifikasi</span>
            @endif
        </x-show.row>

        <x-show.row label="Status">
            {{ ucwords($user->status) }}
        </x-show.row>

        <x-show.row label="Role">
            {{ ucwords($user->role) }}
        </x-show.row>

        <x-show.row label="Cabang">
            {{ $user->branches?->name ?? $user->branch_id }}
        </x-show.row>

        <x-show.row label="Bergabung">
            {{ $user->created_at->format('d F Y H:i:s') }}
        </x-show.row>

        <x-show.row label="Terakhir Akses" class="border-b-none">
            {{ $user->updated_at->format('d F Y H:i:s') }}
        </x-show.row>
    </x-show.card>
</div>


<x-show.actions>
    <x-link-button href="{{ route('user.index') }}">Kembali</x-link-button>

    @can('update', $user)
        <x-link-button href="{{ route('user.edit', $user->id) }}" variant="primary">Edit</x-link-button>
    @endcan

    @can('delete', $user)
        <x-link-button href="{{ route('user.delete', $user->id) }}" variant="danger">Hapus</x-link-button>
    @endcan
</x-show.actions>

@endsection