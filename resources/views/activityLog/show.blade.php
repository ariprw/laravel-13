@extends('layouts.app')

@section('title', $title)

@section('content')

@php
    $subject = $activityLog->subject;

    $subjectName = data_get($subject, 'name')
        ?? data_get($subject, 'title')
        ?? $subject?->getKey()
        ?? '';
@endphp

<div class="grid gap-6">
    <x-show.card>
        <x-slot:title>Detail</x-slot:title>

        <x-show.row label="ID">
            {{ $activityLog->id }}
        </x-show.row>

        <x-show.row label="Nama Log">
            {{ $activityLog->log_name }}
        </x-show.row>

        <x-show.row label="Deskripsi">
            {{ $activityLog->description }}
        </x-show.row>

        <x-show.row label="ID Subjek">
            {{ $activityLog->subject_id }}
        </x-show.row>

        <x-show.row label="Tipe Subjek">
            {{ class_basename($activityLog->subject_type) }}
        </x-show.row>

        <x-show.row label="Subjek">
            {{ $subjectName }}
        </x-show.row>

        <x-show.row label="Dibuat">
            {{ $activityLog->created_at->format('d F Y H:i:s') }}
        </x-show.row>

        <x-show.row label="Terupdate" class="border-b-none">
            {{ $activityLog->updated_at->format('d F Y H:i:s') }}
        </x-show.row>
    </x-show.card>

    @foreach($activityLog->properties as $keys => $items)
        <x-show.card>
            <x-slot:title>
                @switch($keys)
                    @case('old')
                        Data Sebelum
                        @break

                    @case('new')
                        Data Sesudah
                        @break

                    @case('activity')
                        Informasi Tambahan
                        @break

                    @default
                        {{ str($keys)->replace('_', ' ')->title() }}
                @endswitch
            </x-slot:title>

            @if(is_array($items))
                @foreach($items as $key => $item)
                    <x-show.row label="{{ $key }}" class="{{ $loop->last ? 'border-b-none' : '' }}">
                        {{ is_scalar($item) ? $item : json_encode($item) }}
                    </x-show.row>
                @endforeach
            @else
                <x-show.row label="{{ $keys }}" class="{{ $loop->last ? 'border-b-none' : '' }}">
                    {{ $items }}
                </x-show.row>
            @endif
        </x-show.card>
    @endforeach

    <x-show.card>
        <x-slot:title>Akun</x-slot:title>

        <x-show.row label="ID User">
            {{ $activityLog->user_id }}
        </x-show.row>

        <x-show.row label="Nama">
            {{ $activityLog->user?->name ?? $activityLog->user_id }}
        </x-show.row>

        <x-show.row label="Cabang" class="border-b-none">
            {{ $activityLog->user?->branches?->name ?? '' }}
        </x-show.row>
    </x-show.card>
</div>


<x-show.actions>
    <x-link-button href="{{ route('activity.log.index') }}">Kembali</x-link-button>
</x-show.actions>

@endsection