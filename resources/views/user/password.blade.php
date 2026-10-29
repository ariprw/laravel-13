@extends('layouts.app')

@section('title', $title)

@section('content')

<form class="formSubmit" method="POST" action="{{ route('user.password.update') }}">
    <x-show.card>
        <x-slot:title>
            Keamanan Akun
        </x-slot:title>

        <x-slot:subtitle>
            Kelola password untuk menjaga keamanan dan melindungi akun.
        </x-slot:subtitle>

        <div class="grid gap-4">
            <x-form.row-input label="Password Lama *" type="password" name="current_password" required />

            <x-form.row-input label="Password Baru *" type="password" name="password" required />

            <x-form.row-input label="Konfirmasi Password *" type="password" name="password_confirmation" required />
        </div>
    </x-show.card>


    <x-show.actions>
        @csrf
        <x-link-button href="{{ route('dashboard') }}">Batal</x-link-button>

        <x-button type="submit">Simpan</x-button>
    </x-show.actions>
</form>

<x-form.submit/>

@endsection