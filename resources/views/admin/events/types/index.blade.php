@extends('layouts.app')

@section('title', 'Tipe Partisipan - ' . $event->name)

@section('content')
<div class="mb-4 flex justify-between">
    <x-link-button href="{{ route('admin.events.index') }}" variant="secondary" size="sm" icon="arrow_back" title="Kembali" />

    <x-button onclick="openCreateModal()" variant="primary" size="sm" icon="add">
        Tambah Tipe
    </x-button>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto p-4">
    <table class="w-full text-sm datatable">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Deskripsi</th>
                <th class="px-4 py-3 text-left">Teks Sertifikat</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($types as $type)
            <tr class="border-t">
                <td class="px-4 py-3 font-medium">{{ $type->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $type->description ?: '-' }}</td>
                <td class="px-4 py-3 text-gray-500 italic">{{ $type->certificate_text ?: 'Default' }}</td>
                <td class="px-4 py-3 text-center space-x-2">
                    <x-button size="sm" variant="info" icon="edit" title="Edit Tipe"
                        onclick="openEditModal(this)"
                        data-action="{{ route('admin.events.types.update', [$event, $type]) }}"
                        data-name="{{ $type->name }}"
                        data-description="{{ $type->description }}"
                        data-certificate-text="{{ $type->certificate_text }}" />

                    <form action="{{ route('admin.events.types.destroy', [$event, $type]) }}" method="POST" class="inline" onsubmit="return confirmDelete(this)">
                        @csrf
                        @method('DELETE')
                        <x-button size="sm" type="submit" variant="danger" icon="delete" title="Hapus Tipe" />
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-6 text-center text-gray-500">Belum ada tipe partisipan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL FORM --}}
<x-modal id="typeModal" title="Form Tipe Partisipan">
    <form id="typeForm" method="POST">
        @csrf
        <div id="methodField"></div>
        <div class="space-y-4">
            <x-input id="inputName" label="Nama Tipe" name="name" required />
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                <textarea name="description" id="inputDescription" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
            </div>
            <x-input id="inputCertText" label="Teks Sertifikat (Opsional)" name="certificate_text" placeholder="Contoh: atas partisipasinya sebagai" />
        </div>
        <div class="mt-6 flex justify-end space-x-2">
            <x-button type="button" data-modal-hide="typeModal" variant="secondary" size="sm">Batal</x-button>
            <x-button type="submit" variant="primary" size="sm">Simpan</x-button>
        </div>
    </form>
</x-modal>

@push('scripts')
    <x-swal-del-confirm data="Tipe Partisipan" message="Pastikan Tidak ada peserta yang terdaftar dengan tipe partisipan ini"/>
    <script>
        const modal = document.getElementById('typeModal');
        const form = document.getElementById('typeForm');
        const methodField = document.getElementById('methodField');
        const inputName = document.getElementById('inputName');
        const inputDescription = document.getElementById('inputDescription');

        function openCreateModal() {
            form.action = "{{ route('admin.events.types.store', $event) }}";
            methodField.innerHTML = '';
            form.reset();
            showModal();
        }

        function openEditModal(btn) {
            const { action, name, description } = btn.dataset;
            form.action = action;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            inputName.value = name;
            inputDescription.value = description || '';
            document.getElementById('inputCertText').value = btn.dataset.certificateText || '';
            showModal();
        }

        function showModal() {
            // Because x-modal might use Flowbite, we can trigger it via data-modal-toggle
            // or manually if we need to. Since we added data-modal-target earlier, 
            // but here we are using JS.
            // Let's assume the previous x-modal doesn't auto-show via JS easily without Flowbite JS initialized.
            // I'll use the classes I saw earlier.
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.querySelectorAll('[data-modal-hide="typeModal"]').forEach(btn => {
            btn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        });
    </script>
@endpush
@endsection
