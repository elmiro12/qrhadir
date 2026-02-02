@extends('layouts.app')

@section('title', 'Data Peserta')
@section('page-desc', 'Master data seluruh peserta (Global)')

@section('content')

<div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    <x-button
        data-modal-target="participantModal"
        data-modal-toggle="participantModal"
        onclick="openCreateModal()" variant="primary" size="sm">
        + Tambah Peserta Baru
    </x-button>
</div>

<div class="bg-white rounded shadow overflow-x-auto p-4">
    <table class="w-full text-sm datatable">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">No HP</th>
                <th class="px-4 py-3 text-center">Tgl Terdaftar</th>
                @if(auth()->user()->isSuperAdmin())
                    <th class="px-4 py-3 text-center">Dibuat Oleh</th>
                @endif
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $row)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $row->name }}</td>
                    <td class="px-4 py-3">{{ $row->email }}</td>
                    <td class="px-4 py-3">{{ $row->phone }}</td>
                    <td class="px-4 py-3 text-center">@tanggalWaktuIndo($row->created_at)</td>
                    @if(auth()->user()->isSuperAdmin())
                        <td class="px-4 py-3 text-center text-xs">
                            <div class="font-medium text-gray-900">{{ $row->owner->name ?? 'System' }}</div>
                            <div class="text-gray-500">{{ $row->owner->email ?? '-' }}</div>
                        </td>
                    @endif
                    <td class="px-4 py-3 text-center space-x-2">
                        <x-button size="sm"
                            variant="info"
                            icon="edit"
                            title="Edit Peserta"
                            onclick="openEditModal(this)"
                            data-modal-target="participantModal"
                            data-modal-toggle="participantModal"
                            data-action="{{ route('admin.participants.update', $row) }}"
                            data-name="{{ $row->name }}"
                            data-email="{{ $row->email }}"
                            data-phone="{{ $row->phone }}" />

                        <form method="POST"
                              action="{{ route('admin.participants.destroy', $row) }}"
                              class="inline"
                              onsubmit="return confirmDelete(this)">
                            @csrf @method('DELETE')
                            <x-button size="sm" variant="danger" type="submit" icon="delete" title="Hapus Peserta" />
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                        Belum ada data peserta
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


{{-- MODAL FORM --}}
<x-modal id="participantModal" title="Master Data Peserta">
    <form id="participantForm" method="POST">
        @csrf
        <div id="methodField"></div>

        <div class="space-y-4">
            <x-input id="inputName" label="Nama" name="name" required />
            <x-input id="inputEmail" label="Email" name="email" type="email" />
            <x-input id="inputPhone" label="No HP" name="phone" />
        </div>

        <div class="mt-6 flex justify-end space-x-2">
            <x-button type="button" onclick="closeModal()" variant="secondary" size="sm">Batal</x-button>
            <x-button type="submit" variant="primary" size="sm">Simpan</x-button>
        </div>
    </form>
</x-modal>

@push('scripts')
    <x-swal-del-confirm data="Peserta Global"/>
    <script>
        const modal = document.getElementById('participantModal');
        const form = document.getElementById('participantForm');
        const methodField = document.getElementById('methodField');
        
        // Input Elements
        const inputName = document.getElementById('inputName');
        const inputEmail = document.getElementById('inputEmail');
        const inputPhone = document.getElementById('inputPhone');

        function openCreateModal() {
            // Reset Form (Method POST)
            form.action = "{{ route('admin.participants.store') }}";
            methodField.innerHTML = ''; 
            form.reset();
            
        }

        function openEditModal(btn) {
            // Parse Data
            const { action, name, email, phone } = btn.dataset;

            // Set Action & Method PUT
            form.action = action;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            // Fill Values
            inputName.value = name;
            inputEmail.value = email || '';
            inputPhone.value = phone || '';

        }
    </script>
@endpush

@endsection
