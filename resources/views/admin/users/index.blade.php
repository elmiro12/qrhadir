@extends('layouts.app')

@section('title', 'Manajemen Akun')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- List Account --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                 <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-icons text-red-600">manage_accounts</span>
                    Daftar Admin Event
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-right flex justify-end gap-2">
                                    {{-- Edit Modal Trigger --}}
                                    <x-button 
                                        onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                        variant="dark" size="sm" icon="edit" title="Edit Data"
                                    />

                                    {{-- Reset Password --}}
                                    <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" onsubmit="return confirm('Reset password akun ini menjadi password123?')">
                                        @csrf
                                        <x-button type="submit" variant="warning" size="sm" icon="lock_reset" title="Reset Password" />
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="danger" size="sm" icon="delete" title="Hapus Akun" />
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400 italic">Belum ada akun Admin Event.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Form --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-20">
            <div class="p-6">
                <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-icons text-green-600">person_add</span>
                    Tambah Akun Baru
                </h2>

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <x-input name="name" label="Nama Lengkap" placeholder="Masukkan nama..." required />
                    <x-input type="email" name="email" label="Email" placeholder="email@example.com" required />
                    <x-input type="password" name="password" label="Password" required />
                    <x-input type="password" name="password_confirmation" label="Konfirmasi Password" required />
                    
                    <div class="pt-2">
                        <x-button type="submit" variant="secondary" class="w-full" icon="save">Simpan Akun</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold">Edit Akun</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><span class="material-icons">close</span></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <x-input id="edit_name" name="name" label="Nama Lengkap" required />
            <x-input id="edit_email" type="email" name="email" label="Email" required />
            
            <div class="pt-4 flex justify-between gap-2">
                <x-button type="button" onclick="closeEditModal()" variant="dark">Batal</x-button>
                <x-button type="submit" variant="secondary" icon="save">Update Data</x-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, name, email) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editForm').action = `{{ url('admin/users') }}/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
