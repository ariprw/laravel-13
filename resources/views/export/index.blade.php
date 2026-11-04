@extends('layouts.app')

@section('title', $title)

@section('content')

<x-show.card>
    <div class="notifExport"></div>

    <div class="grid gap-4">
        <x-form.row-select
        label="Kategori *"
        name="category"
        :options="$category"
        placeholder="-- Pilih --"
        />

        <div class="contentStartDate">
            <x-form.row-input
            label="Dari *"
            type="text"
            name="startDate"
            placeholder="Tahun / Bulan / Hari"
            onkeypress="return dateKeyPress(event)"
            maxlength="10"
            autocomplete="off"
            class="datePicker"
            />
        </div>

        <div class="contentEndDate">
            <x-form.row-input
            label="Sampai *"
            type="text"
            name="endDate"
            placeholder="Tahun / Bulan / Hari"
            onkeypress="return dateKeyPress(event)"
            maxlength="10"
            autocomplete="off"
            class="datePicker"
            />
        </div>
    </div>
</x-show.card>


<x-show.actions>
    @csrf
    <x-link-button href="{{ $link }}">Batal</x-link-button>
    <x-div-button class="buttonExport">Export</x-div-button>
</x-show.actions>

<x-js.submit/>
<x-js.date-picker/>
<x-js.custom-select/>
<script>
    title = '{{ $title }}';
    module = '{{ $module }}';
    urlExport = '{{ route('export.store') }}';
</script>
<script src="{{ url('/js/excel.js') }}"></script>
<script src="{{ url('/js/export.js') }}"></script>

@endsection