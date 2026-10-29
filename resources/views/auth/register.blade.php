@extends('layouts.auth')

@section('title', $title)

@section('content')

    <div class="mb-9">
        <h1 class="text-xl font-semibold text-slate-900">{{ $title }}</h1>

        <p class="mt-1 text-sm text-slate-500">Daftarkan akun untuk mengakses {{ config('app.name') }}</p>
    </div>

    <form class="formSubmit" method="POST" action="{{ route('register.store') }}">
        <div class="mb-5">
            <div class="mb-2 block text-sm font-medium text-slate-700">Nama Lengkap *</div>
            
            <x-form.input type="text" name="name" autofocus required />
        </div>

        <div class="mb-5">
            <div class="mb-2 block text-sm font-medium text-slate-700">Email *</div>
            
            <x-form.input type="email" name="email" required />
        </div>

        <div class="mb-5">
            <div class="mb-2 block text-sm font-medium text-slate-700">Password *</div>
            
            <x-form.input type="password" name="password" required />
        </div>

        <div class="mb-5">
            <div class="mb-2 block text-sm font-medium text-slate-700">Ulangi Password *</div>
            
            <x-form.input type="password" name="password_confirmation" required />
        </div>

        <div class="my-9">
            @csrf
            <x-button class="w-full" type="submit">Daftar</x-button>
        </div>
    </form>

    <div class="text-center">
        <p class="text-sm text-slate-500">
            Sudah punya akun?
            <a class="font-medium text-indigo-700 hover:text-black" href="{{ route('login') }}">Login</a>
        </p>
    </div>

    <x-form.submit/>

@endsection