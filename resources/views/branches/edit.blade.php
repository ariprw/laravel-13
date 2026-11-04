@extends('layouts.app')

@section('title', $title)

@section('content')

<form class="formSubmit" method="POST" action="{{ route('branches.update', $branches->id) }}" enctype="multipart/form-data">
    <x-show.card>
        <x-slot:title>
            Cabang
        </x-slot:title>

        <x-slot:subtitle>
            Digunakan untuk menyimpan informasi cabang yang mendukung kegiatan operasional, administrasi dan informasi publik.
        </x-slot:subtitle>

        <div class="grid gap-4">
            <x-form.row-input
                label="Nama *"
                type="text"
                name="name"
                value="{{old('name', $branches->name)}}"
                required
            />

            <x-form.row-input
                label="Ponsel"
                sublabel="Nomor whatsapp yang aktif"
                type="text"
                name="phone"
                value="{{old('phone', $branches->phone)}}"
                datasetFormat="number"
            />

            <x-form.row-input
                label="Email"
                type="email"
                name="email"
                value="{{old('email', $branches->email)}}"
            />

            <x-form.row-input
                label="Alamat"
                type="text"
                name="address"
                value="{{old('address', $branches->address)}}"
                maxlength="800"
            />

            <x-form.row-input
                label="Maps"
                sublabel="Tautan lokasi dari google maps"
                type="text"
                name="maps"
                value="{{old('maps', $branches->maps)}}"
                maxlength="800"
            />

            <x-form.row-select
                label="Status *"
                name="status"
                :options="$status"
                :value="$branches->status"
                placeholder="-- Pilih --"
                required
            />

            <x-form.row-input
                label="Logo"
                sublabel="Harus JPG / PNG dan Maks. 10 MB"
                type="file"
                name="logo"
                value="{{old('logo', $branches->logo)}}"
                accept="image/jpeg,image/png"
            />

            @if ($branches->logo)
                <x-form.row-img
                    label="Logo Saat Ini"
                    sublabel="Klik gambar untuk memperbesar"
                    href="{{ asset($branches->logo) }}"
                    src="{{ asset($branches->logo) }}"
                    target="_blank"
                    remove="true"
                    name="removeLogo"
                />
            @endif
        </div>
    </x-show.card>


    <x-show.actions>
        @csrf
        <x-link-button href="{{ route('branches.index') }}">Batal</x-link-button>

        <x-button type="submit">Simpan</x-button>
    </x-show.actions>
</form>

<x-js.submit/>
<x-js.input-format/>
<x-js.input-file/>
<x-js.custom-select/>

@endsection