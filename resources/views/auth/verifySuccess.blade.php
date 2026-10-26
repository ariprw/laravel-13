@extends('layouts.auth')

@section('title', $title)

@section('content')

    <div class="mb-9">
        <h1 class="text-xl font-semibold text-slate-900">{{ $title }}</h1>
    </div>

    <x-notif type="success">
        {{ $status }}
    </x-notif>

    <div class="text-center mt-9">
        <p class="text-sm text-slate-500">
            Sudah punya akun?
            <a class="font-medium text-lime-700 hover:text-black" href="{{ route('login') }}">Login</a>
        </p>
    </div>

@endsection