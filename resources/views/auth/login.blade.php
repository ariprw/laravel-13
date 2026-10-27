@extends('layouts.auth')

@section('title', $title)

@section('content')

    <div class="mb-9">
        <h1 class="text-xl font-semibold text-slate-900">{{ $title }}</h1>

        <p class="mt-1 text-sm text-slate-500">Masuk ke akun Anda</p>
    </div>

    @if (session('status'))
        <x-notif type="success">
            {{ session('status') }}
        </x-notif>
    @endif

    <form class="formSubmit" method="POST" action="{{ route('login') }}">
        <div class="mb-5">
            <div class="mb-2 block text-sm font-medium text-slate-700">Email *</div>

            <x-form.input type="email" name="email" autofocus required />
        </div>

        <div class="mb-5">
            <div class="block text-sm font-medium text-slate-700">Password *</div>

            <x-form.input type="password" name="password" required />
        </div>

        <div class="flex justify-end">
            <a class="text-sm text-indigo-700 hover:text-black select-none" href="{{ route('forgot') }}">Lupa password?</a>
        </div>

        <div class="my-9">
            @csrf
            <x-button class="w-full" type="submit">Login</x-button>
        </div>
    </form>

    <div class="text-center">
        <p class="text-sm text-slate-500">
            Belum punya akun?
            <a class="font-medium text-indigo-700 hover:text-black" href="{{ route('register') }}">Daftar</a>
        </p>
    </div>
    
    {!! formSubmitJs() !!}

@endsection