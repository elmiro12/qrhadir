@extends('layouts.app')

@section('title', 'Event')
@section('page-desc', 'Daftar event yang terdaftar di sistem')

@section('content')

<div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <x-link-button href="{{ route('admin.events.create') }}" variant="primary">
        + Tambah Event
    </x-link-button>
</div>

</div>


@php
    $now = now();
@endphp

<div class="bg-white rounded-lg shadow overflow-x-auto p-4">
    <table class="w-full text-sm datatable">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-center">Tanggal</th>
                <th class="px-4 py-3 text-center">Status</th>
                @if(auth()->user()->isSuperAdmin())
                    <th class="px-4 py-3 text-center">Dibuat Oleh</th>
                @endif
                <th class="px-4 py-3 text-center">QR Event</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($events as $event)

            @php
                // badge status
                $badgeType = match ($event->status) {
                    'active' => 'success',
                    'closed' => 'danger',
                    default  => 'default',
                };

                // next status button
                $nextStatus = $event->status === 'active' ? 'closed' : 'active';
                $nextIcon   = $event->status === 'active' ? 'lock' : 'check_circle';
                $nextBtn    = $event->status === 'active' ? 'danger' : 'success';
            @endphp

            <tr class="border-t align-top">
                {{-- Logo & Nama --}}
                <td class="px-4 py-3 font-medium">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100">
                            @if($event->logo)
                                <img src="{{ $event->logo_url }}" alt="{{ $event->name }}" class="w-full h-full object-contain">
                            @else
                                <span class="material-icons text-gray-300">event</span>
                            @endif
                        </div>
                        <div>
                            {{ $event->name }}
                        </div>
                    </div>
                </td>

                {{-- Tanggal --}}
                <td class="px-4 py-3 text-center">
                    <div>@tanggalWaktuIndo($event->start_date)</div>
                    <div class="text-gray-500 text-xs">s/d</div>
                    <div>@tanggalWaktuIndo($event->end_date)</div>
                </td>

                {{-- Status --}}
                <td class="px-4 py-3 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <x-badge :type="$badgeType">
                            {{ strtoupper($event->status) }}
                        </x-badge>
                        
                        {{-- Status Actions Dropdown --}}
                        <x-dropdown id="statusActions-{{ $event->id }}" label="" icon="settings" size="sm" variant="secondary">
                            <x-dropdown-header label="Ubah Status" />
                            
                            {{-- Toggle Active/Closed --}}
                            <x-dropdown-item type="button" icon="{{ $nextIcon }}" 
                                onclick="document.getElementById('status-form-{{ $event->id }}').submit()">
                                {{ $nextStatus === 'closed' ? 'Tutup Event' : 'Aktifkan Event' }}
                            </x-dropdown-item>

                            {{-- Back to Draft --}}
                            @if ($event->status !== 'draft')
                                <x-dropdown-item type="button" icon="drafts" 
                                    onclick="document.getElementById('draft-form-{{ $event->id }}').submit()">
                                    Kembali ke Draft
                                </x-dropdown-item>
                            @endif
                        </x-dropdown>
                    </div>
                </td>

                {{-- Dibuat Oleh --}}
                @if(auth()->user()->isSuperAdmin())
                <td class="px-4 py-3 text-center">
                    <div class="font-medium text-gray-900">{{ $event->owner->name ?? 'System' }}</div>
                    <div class="text-xs text-gray-500">{{ $event->owner->email ?? '-' }}</div>
                </td>
                @endif

                {{-- QR Event --}}
                <td class="px-4 py-3 text-center">
                    @switch(true)
                        @case($event->status === 'draft')
                            <x-badge>QR Belum Tersedia</x-badge>
                            @break

                        @case($event->status === 'active' && $now->lte($event->end_date))
                            <div class="inline-block bg-white p-2 rounded shadow">
                                {!! QrCode::size(120)->generate(eventRegisterUrl($event)) !!}
                            </div>
                            <div class="mt-2 space-y-1 text-xs text-gray-500 flex flex-col items-center">
                                <x-link-button
                                    href="{{ eventRegisterUrl($event) }}"
                                    size="xs"
                                    icon="open_in_new"
                                    target="_blank"
                                    class="w-full">
                                    Portal Registrasi
                                </x-link-button>

                                <x-link-button
                                    href="{{ route('admin.events.print-qr', $event) }}"
                                    size="xs"
                                    variant="dark"
                                    icon="print"
                                    target="_blank"
                                    class="w-full">
                                    Cetak QR
                                </x-link-button>
                            </div>
                            @break
                        @default
                            <x-badge type="danger">Event Sudah Selesai</x-badge>
                    @endswitch
                </td>

                {{-- Aksi --}}
                <td class="px-4 py-3 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center gap-1">
                        {{-- Standalone: Daftar Peserta --}}
                        <x-link-button
                            variant="primary"
                            href="{{ route('admin.events.participants.index', $event) }}"
                            size="sm"
                            title="Daftar Peserta"
                            class="!px-2">
                            <x-icon name="groups"/>
                        </x-link-button>

                        {{-- Standalone: Edit Event --}}
                        <x-link-button
                            variant="secondary"
                            href="{{ route('admin.events.edit', $event) }}"
                            size="sm"
                            title="Edit Event"
                            class="!px-2">
                            <x-icon name="edit"/>
                        </x-link-button>

                        {{-- Dropdown: Lainnya --}}
                        <x-dropdown id="eventActions-{{ $event->id }}" label="" icon="more_vert" size="sm" variant="secondary">
                            
                            <x-dropdown-header label="Kelola Data" />
                            <x-dropdown-item href="{{ route('admin.events.types.index', $event) }}" icon="category">
                                Tipe Partisipan
                            </x-dropdown-item>

                            <x-dropdown-header label="Media & Output" />
                            <x-dropdown-item href="{{ route('admin.reports.show', $event) }}" icon="assessment">
                                Laporan Absensi
                            </x-dropdown-item>
                            <x-dropdown-item href="{{ route('admin.events.id-cards.template.show', $event) }}" icon="style">
                                Template ID Card
                            </x-dropdown-item>
                            <x-dropdown-item href="{{ route('admin.events.print-qr', $event) }}" icon="print" target="_blank">
                                Cetak QR Event
                            </x-dropdown-item>
                            <x-dropdown-item href="{{ route('admin.events.certificates.index', $event) }}" icon="workspace_premium">
                                Kelola Sertifikat
                            </x-dropdown-item>

                            <x-dropdown-header label="Akun" />
                            @if(auth()->user()->isSuperAdmin())
                                <x-dropdown-item type="button" icon="swap_horiz" 
                                    onclick="openTransferModal({{ $event->id }}, '{{ $event->name }}')">
                                    Transfer Kepemilikan
                                </x-dropdown-item>
                            @endif

                            <x-dropdown-header label="Tindakan Berbahaya" />
                            <x-dropdown-item type="button" icon="delete" variant="danger" 
                                onclick="confirmDelete(document.getElementById('delete-event-{{ $event->id }}'))">
                                Hapus Event
                            </x-dropdown-item>
                        </x-dropdown>

                        {{-- Hidden Forms --}}
                        <form id="status-form-{{ $event->id }}" action="{{ route('admin.events.set-status', $event) }}"
                              method="POST" class="hidden">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                        </form>
                        <form id="draft-form-{{ $event->id }}" action="{{ route('admin.events.set-status', $event) }}"
                              method="POST" class="hidden">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="draft">
                        </form>
                        <form id="delete-event-{{ $event->id }}" action="{{ route('admin.events.destroy', $event) }}"
                              method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                    Belum ada event
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Transfer Modal --}}
@if(auth()->user()->isSuperAdmin())
<div id="transferModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold">Pindahkan Kepemilikan Event</h3>
            <button onclick="closeTransferModal()" class="text-gray-400 hover:text-gray-600"><span class="material-icons">close</span></button>
        </div>
        <form id="transferForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <p class="text-sm text-gray-600 mb-4">
                    Pindahkan event <b id="transferEventName"></b> beserta seluruh pesertanya ke akun admin lain.
                </p>
                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Admin Event Baru</label>
                <select name="user_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    <option value="">-- Pilih Admin --</option>
                    @foreach($adminEvents as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="pt-4 flex justify-between gap-2">
                <x-button type="button" onclick="closeTransferModal()" variant="dark">Batal</x-button>
                <x-button type="submit" variant="primary" icon="swap_horiz">Pindahkan Sekarang</x-button>
            </div>
        </form>
    </div>
</div>
@endif


@push('scripts')
    <x-swal-del-confirm data="Event"/>

    <script>
        function openTransferModal(id, name) {
            document.getElementById('transferModal').classList.remove('hidden');
            document.getElementById('transferForm').action = `{{ url('admin/events') }}/${id}/transfer`;
            document.getElementById('transferEventName').innerText = name;
        }

        function closeTransferModal() {
            document.getElementById('transferModal').classList.add('hidden');
        }

        function confirmSetStatus(form) {
            Swal.fire({
                title: 'Konfirmasi Tutup Event',
                text: 'Menutup event akan menutup portal registrasi',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, tutup',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return false;
        }

        function showQrModal(name, url) {
            const modalTitle = document.getElementById('qrModalTitle');
            const modalQr = document.getElementById('qrModalContent');
            const modalLink = document.getElementById('qrModalLink');
            
            modalTitle.innerText = name;
            modalLink.innerText = url;
            modalLink.href = url;
            
            // Generate QR via API or just use the same library if client-side, 
            // but since we use server-side generation, we might need an AJAX route or just iframe,
            // OR simpler: we create a specific route to render just the QR code for printing.
            
            // For now, let's just cheat and put the link in the modal using an IMG tag if we had an API,
            // but we don't.
            // Alternative: Open a new window with the "Ticket" view but for the Event URL? No.
            
            // Let's use a specialized route to get the component, OR just use the helper to open a new tab for "Presentation Mode".
            // Actually, simply opening the registration page is enough for projection? No, we want the QR.
        }
    </script>
    
    {{-- Since generating QR client side is easier for modals without AJAX, let's use a simple JS library or just keep it simple --}}
    {{-- Actuallly, the prompt asked for "Admin export / cetak ... Print browser". --}}
    {{-- Let's just create a 'print-qr' route for the event. --}}
@endpush

@endsection
