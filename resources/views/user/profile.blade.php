@extends('layouts.app')

@section('title', $title)

@section('content')

<form class="formSubmit" method="POST" action="{{ route('user.profile.update') }}">
    <x-show.card>
        <x-slot:title>
            Personal
        </x-slot:title>

        <x-slot:subtitle>
            Informasi ini tidak akan ditampilkan secara public.
        </x-slot:subtitle>

        <div class="grid gap-4">
            <x-form.row-input label="Nama Lengkap *" type="text" name="name" value="{{old('name', $user->name)}}" required />

            <x-form.row-read-only label="Email">{{ $user->email }}</x-form.row-read-only>

            <x-form.row-read-only label="Role">{{ $user->role }}</x-form.row-read-only>

            <x-form.row-read-only label="Cabang">{{ $user->branches?->name ?? ($user->branch_id ?? '-') }}</x-form.row-read-only>
        </div>
    </x-show.card>


    <x-show.actions>
        @csrf
        <x-link-button href="{{ route('dashboard') }}">Batal</x-link-button>

        <x-button type="submit">Simpan</x-button>
    </x-show.actions>
</form>

{!! formSubmitJs() !!}

@endsection