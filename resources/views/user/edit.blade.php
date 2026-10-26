@extends('layouts.app')

@section('title', $title)

@section('content')

<form class="formSubmit" method="POST" action="{{ route('user.update', $user->id) }}">
    <x-show.card>
        <x-slot:title>
            Personal
        </x-slot:title>

        <x-slot:subtitle>
            Informasi ini tidak akan ditampilkan secara public.
        </x-slot:subtitle>

        <div class="grid gap-4">
            <x-form.row-input label="Nama Lengkap *" type="text" name="name" value="{{old('name', $user->name)}}" required />

            <x-form.row-read-only label="Email">{{ $user->email }}</x-form.row-read-only>
        
            <x-form.row-read-only label="Email Diverifikasi">
                @if ($user->email_verified_at)
                    {{ $user->email_verified_at->format('d F Y H:i:s') }}
                @else
                    Belum Terverifikasi
                @endif
            </x-form.row-read-only>

            <x-form.row-select
                label="Status *"
                name="status"
                :options="$status"
                :value="$user->status"
                placeholder="-- Pilih --"
                required
            />

            <x-form.row-select
                label="Role *"
                name="role"
                :options="$role"
                :value="$user->role"
                placeholder="-- Pilih --"
                required
            />

            <x-form.row-select
                label="Cabang"
                name="branch"
                :options="[]"
                :value="old('branch', $user->branch_id)"
                placeholder="-- Kosong --"
            />
        </div>
    </x-show.card>


    <x-show.actions>
        @csrf
        <x-link-button href="{{ route('user.index') }}">Batal</x-link-button>

        <x-button type="submit">Simpan</x-button>
    </x-show.actions>
</form>

{!! customSelect().formSubmitJs() !!}
<script>
    const branchSelect = document.querySelector('.customSelect select[name="branch"]');
    let branchSelectLabel = branchSelect?.firstElementChild?.innerText?.trim() ?? '';
    let branchSelectValue = '{{ old('branch', $user->branch_id) }}';
    const debouncedGetBranches = debounce(getBranches, 800);

    document.addEventListener('DOMContentLoaded', async () => {
        await getBranches();
    });

    document.addEventListener('keyup', (e) => {
        const customSelect = e.target.closest('.customSelect');

        if (!customSelect) return;

        const selectElement = customSelect.querySelector('select');

        if (!selectElement || selectElement.name !== 'branch') return;

        lastTypedCustomSelect = customSelect;

        debouncedGetBranches(e.target.value);
    });

    function getBranches(value='') {
        fetch('{{ route('branches.search') }}?q='+encodeURIComponent(value))
        .then(response => response.json())
        .then((data) => {
            branchSelect.innerHTML = `<option value="" selected>${branchSelectLabel}</option>`;

            if (data.data) {
                data.data.forEach((item) => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.innerHTML = `${item.name}<div class="text-xs text-gray-500 italic">${item.statusLabel}</div>`;

                    if (item.id == branchSelectValue) {
                        option.selected = true;
                    }

                    branchSelect.appendChild(option);
                });

                if (data.data.length <= 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = `Tidak Ditemukan!`;

                    branchSelect.appendChild(option);
                }
            }
        })
        .catch(err => {
        })
        .finally(() => {
            initCustomSelect(0);
            openLastCustomSelect(value);
            lastTypedCustomSelect = null;
        });
    }

    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(this, [...args]);
            }, delay);
        };
    }
</script>

@endsection