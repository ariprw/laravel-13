@extends('layouts.app')

@section('title', $title)

@section('content')

<x-show.card>
    <x-slot:title>
        Konten
    </x-slot:title>

    <p class="text-slate-600">{{ Str::random(5000) }}</p>
</x-show.card>

@endsection