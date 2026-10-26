@extends('layouts.app')

@section('title', $title)

@section('content')

<form class="formSubmit" method="POST" action="{{ route('branches.store') }}">
    <x-show.card>
        <x-slot:title>
            Cabang
        </x-slot:title>

        <x-slot:subtitle>
            Digunakan untuk menentukan lokasi kerja user.
        </x-slot:subtitle>
        
        <div class="grid gap-4">
            <x-form.row-input label="Nama *" type="text" name="name" value="{{old('name')}}" required />

            <x-form.row-select
                label="Status *"
                name="status"
                :options="$status"
                placeholder="-- Pilih --"
                required
            />
        </div>
    </x-show.card>


    <x-show.actions>
        @csrf
        <x-link-button href="{{ route('branches.index') }}">Batal</x-link-button>

        <x-button type="submit">Simpan</x-button>
    </x-show.actions>
</form>

{!! customSelect().formSubmitJs() !!}

@endsection