@extends('layouts.main')

@section('title', $title)

@section('content')

<div class="min-h-screen max-w-192 flex items-center justify-center m-auto px-4 py-8">
    <div class="w-full">
        <p class="text-base">{{ $title }}. Kembali ke <a class="font-bold text-lime-700 hover:text-black" href="{{ route('home') }}">Beranda</a></p>
    </div>
</div>

@endsection