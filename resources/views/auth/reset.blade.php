@extends('layouts.auth')

@section('title', $title)

@section('content')

    <div class="mb-9">
        <h1 class="text-xl font-semibold text-slate-900">{{ $title }}</h1>

        <p class="mt-1 text-sm text-slate-500">Masuk password baru Anda</p>
    </div>

    @if (session('status'))
        <x-notif type="success">
            {{ session('status') }}
        </x-notif>
    @endif

    <form class="formSubmit" method="POST" action="{{ route('reset.password.store') }}">
        <div class="mb-5">
            <div class="mb-2 block text-sm font-medium text-slate-700">Password Baru *</div>

            <x-form.input type="password" name="password" required />
        </div>

        <div class="mb-5">
            <div class="mb-2 block text-sm font-medium text-slate-700">Ulangi Password *</div>

            <x-form.input type="password" name="password_confirmation" required />
            <x-form.input type="hidden" name="token" value="{{ $token }}" />
            <x-form.input type="hidden" name="email" value="{{ $email }}" />
        </div>

        <div class="my-9">
            @csrf
            <x-button class="w-full" type="submit">Simpan</x-button>
        </div>
    </form>

    <x-form.submit/>

@endsection