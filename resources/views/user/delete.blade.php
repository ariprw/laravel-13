@extends('layouts.app')

@section('title', $title)

@section('content')

<form class="formSubmit" method="POST" action="{{ route('user.destroy', $user->id) }}">
    <x-show.card>
        <x-slot:title>
            Peringatan!
        </x-slot:title>

        <x-slot:subtitle>
            Hati-hati, data yang sudah dihapus tidak dapat dipulihkan.
        </x-slot:subtitle>

        <div class="grid gap-4">
            <x-form.row-read-only label="ID">{{ $user->id }}</x-form.row-read-only>
            <x-form.row-read-only label="Nama Lengkap">{{ $user->name }}</x-form.row-read-only>
            <x-form.row-read-only label="Email">{{ $user->email }}</x-form.row-read-only>

            <x-form.row-input class="text-red-600" label="" type="hidden" name="approval" value="{{ $delete }}" read-only/>
        </div>
    </x-show.card>


    <x-show.actions>
        @csrf
        <x-link-button href="{{ route('user.index') }}">Batal</x-link-button>

        <x-button type="submit" variant="danger">Hapus</x-button>
    </x-show.actions>
</form>

{!! formSubmitJs() !!}

@endsection