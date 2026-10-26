@extends('layouts.auth')

@section('title', $title)

@section('content')

    <div class="mb-9">
        <h1 class="text-xl font-semibold text-slate-900">{{ $title }}</h1>

        <p class="mt-1 text-sm text-slate-500">Masukkan email Anda dan kami akan mengirimkan link untuk reset password</p>
    </div>

    @if (session('status'))
        <x-notif type="success">
            {{ session('status') }}
        </x-notif>
    @endif

    <form class="formSubmit" method="POST" action="{{ route('forgot.store') }}">
        <div class="mb-5">
            <div class="mb-2 block text-sm font-medium text-slate-700">Email *</div>
            
            <x-form.input type="email" name="email" autofocus required />
        </div>

        <div class="my-9">
            @csrf
            <x-button class="w-full" type="submit">Kirim</x-button>
        </div>
    </form>

    <div class="text-center">
        <p class="text-sm text-slate-500">
            Belum menerima email verifikasi?
            <a class="font-medium text-lime-700 hover:text-black" href="{{ route('resend.verification') }}">Kirim ulang</a>
        </p>
    </div>
    
    {!! formSubmitJs() !!}

@endsection