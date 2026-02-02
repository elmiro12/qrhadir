@extends('layouts.login')

@section('title', 'Welcome - ' . setting('app_name'))

@section('content')
<div class="space-y-4">
    
    {{-- 1. Tombol Registrasi (Scroll ke atas IF ada list, else Alert or Link) --}}
    @if(isset($events) && $events->count() > 0)
        <button onclick="document.getElementById('event-list').scrollIntoView({behavior: 'smooth'})" 
            class="w-full group relative flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-red-500 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-red-100 text-red-600 rounded-full">
                    <span class="material-icons">person_add</span>
                </div>
                <div class="text-left">
                    <span class="block font-bold text-gray-800 group-hover:text-red-600 transition">Registrasi Peserta</span>
                    <span class="block text-xs text-gray-500">Daftar event baru & dapatkan QR-Code</span>
                </div>
            </div>
            <span class="material-icons text-gray-300 group-hover:text-red-500">arrow_upward</span>
        </button>
    @else
        <div class="w-full p-4 bg-gray-100 rounded-xl text-center border border-dashed border-gray-300">
            <span class="text-sm text-gray-500">Belum ada event aktif.</span>
        </div>
    @endif

    {{-- 2. Tombol Scan QR (Hadir) --}}
    <a href="{{ route('admin.attendance.scan') }}" 
        class="w-full group relative flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-orange-500 transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-orange-100 text-orange-600 rounded-full">
                <span class="material-icons">qr_code_scanner</span>
            </div>
            <div class="text-left">
                <span class="block font-bold text-gray-800 group-hover:text-orange-600 transition">Scan QR Hadir</span>
                <span class="block text-xs text-gray-500">Scan QR-Code Peserta untuk kehadiran</span>
            </div>
        </div>
        <span class="material-icons text-gray-300 group-hover:text-orange-500">chevron_right</span>
    </a>

    {{-- 3. Tombol Check & Print Ticket --}}
    <button type="button" onclick="openCheckTicketModal()"
        class="w-full group relative flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-blue-500 transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                <span class="material-icons">confirmation_number</span>
            </div>
            <div class="text-left">
                <span class="block font-bold text-gray-800 group-hover:text-blue-600 transition">Check & Print Ticket</span>
                <span class="block text-xs text-gray-500">Lihat & cetak tiket event yang sudah didaftar</span>
            </div>
        </div>
        <span class="material-icons text-gray-300 group-hover:text-blue-500">chevron_right</span>
    </button>

    {{-- 4. Tombol Cek Sertifikat --}}
    <button type="button" onclick="openCheckCertificateModal()"
        class="w-full group relative flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-green-500 transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-green-100 text-green-600 rounded-full">
                <span class="material-icons">workspace_premium</span>
            </div>
            <div class="text-left">
                <span class="block font-bold text-gray-800 group-hover:text-green-600 transition">Cek Sertifikat</span>
                <span class="block text-xs text-gray-500">Download sertifikat event yang telah diikuti</span>
            </div>
        </div>
        <span class="material-icons text-gray-300 group-hover:text-green-500">chevron_right</span>
    </button>

    {{-- 4. Tombol Login Admin --}}
    @if(Auth::guard('admin')->check())
        <a href="{{ route('admin.dashboard') }}" 
           class="w-full group relative flex items-center justify-between p-4 bg-gray-800 border border-transparent rounded-xl shadow-sm hover:shadow-lg hover:bg-gray-900 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-700 text-white rounded-full">
                    <span class="material-icons">dashboard</span>
                </div>
                <div class="text-left">
                    <span class="block font-bold text-white">Dashboard Admin</span>
                    <span class="block text-xs text-gray-400">Kembali ke panel admin</span>
                </div>
            </div>
            <span class="material-icons text-gray-500 group-hover:text-white">chevron_right</span>
        </a>
    @else
        <a href="{{ route('admin.login') }}" 
           class="w-full group relative flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-gray-800 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 text-gray-600 rounded-full">
                    <span class="material-icons">admin_panel_settings</span>
                </div>
                <div class="text-left">
                    <span class="block font-bold text-gray-800 group-hover:text-black transition">Login Admin</span>
                    <span class="block text-xs text-gray-500">Masuk sebagai penyelenggara</span>
                </div>
            </div>
            <span class="material-icons text-gray-300 group-hover:text-black">login</span>
        </a>
    @endif

</div>

{{-- Modal Check Ticket --}}
<div id="checkTicketModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
        <div class="p-6 border-b flex justify-between items-center bg-orange-600 text-white">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <span class="material-icons">confirmation_number</span>
                Cek Tiket Saya
            </h3>
            <button onclick="closeCheckTicketModal()" class="text-white/80 hover:text-white transition">
                <span class="material-icons">close</span>
            </button>
        </div>
        
        <div class="p-6">
            {{-- Form Input --}}
            <div id="checkFormContainer">
                <p class="text-sm text-gray-600 mb-4">Masukkan Email atau Nomor HP yang Anda gunakan saat mendaftar.</p>
                <div class="space-y-4">
                    <x-input id="lookupIdentifier" name="identifier" label="Email / No HP" placeholder="Contoh: 08123... atau email@mail.com" required />
                    <x-button onclick="performTicketLookup()" variant="primary" class="w-full" size="md" id="btnLookup">
                        Cari Tiket
                    </x-button>
                </div>
            </div>

            {{-- Result List --}}
            <div id="checkResultContainer" class="hidden space-y-4">
                <div class="flex items-center gap-3 p-3 bg-orange-50 text-orange-800 rounded-xl border border-blue-100">
                    <span class="material-icons">person</span>
                    <div>
                        <div class="text-xs text-blue-600">Terdaftar sebagai:</div>
                        <div class="font-bold" id="resParticipantName">-</div>
                    </div>
                </div>

                <div class="max-h-64 overflow-y-auto space-y-3" id="ticketList">
                    {{-- Loop data via JS --}}
                </div>

                <x-button onclick="resetCheckModal()" variant="secondary" class="w-full" size="sm">
                    Cari identitas lain
                </x-button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Check Certificate --}}
<div id="checkCertificateModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
        <div class="p-6 border-b flex justify-between items-center bg-green-600 text-white">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <span class="material-icons">workspace_premium</span>
                Cek Sertifikat
            </h3>
            <button onclick="closeCheckCertificateModal()" class="text-white/80 hover:text-white transition">
                <span class="material-icons">close</span>
            </button>
        </div>
        
        <div class="p-6">
            {{-- Form Input --}}
            <div id="checkCertFormContainer">
                <p class="text-sm text-gray-600 mb-4">Masukkan Email atau Nomor HP yang terdaftar.</p>
                <div class="space-y-4">
                    <x-input id="lookupCertIdentifier" name="identifier" label="Email / No HP" placeholder="Contoh: 08123... atau email@mail.com" required />
                    <x-button onclick="performCertificateLookup()" variant="success" class="w-full" size="md" id="btnCertLookup">
                        Cari Sertifikat
                    </x-button>
                </div>
            </div>

            {{-- Result List --}}
            <div id="checkCertResultContainer" class="hidden space-y-4">
                <div class="flex items-center gap-3 p-3 bg-green-50 text-green-800 rounded-xl border border-green-100">
                    <span class="material-icons">person</span>
                    <div>
                        <div class="text-xs text-green-600">Peserta:</div>
                        <div class="font-bold" id="resCertParticipantName">-</div>
                    </div>
                </div>

                <div class="max-h-64 overflow-y-auto space-y-3" id="certList">
                    {{-- Loop data via JS --}}
                </div>

                <x-button onclick="resetCheckCertModal()" variant="secondary" class="w-full" size="sm">
                    Cari identitas lain
                </x-button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCheckTicketModal() {
        document.getElementById('checkTicketModal').classList.remove('hidden');
        resetCheckModal();
    }

    function closeCheckTicketModal() {
        document.getElementById('checkTicketModal').classList.add('hidden');
    }

    function resetCheckModal() {
        document.getElementById('checkFormContainer').classList.remove('hidden');
        document.getElementById('checkResultContainer').classList.add('hidden');
        document.getElementById('lookupIdentifier').value = '';
        document.getElementById('ticketList').innerHTML = '';
    }

    async function performTicketLookup() {
        const identifier = document.getElementById('lookupIdentifier').value;
        if(!identifier) return;

        const btn = document.getElementById('btnLookup');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Mencari...';

        try {
            const response = await fetch("{{ route('event.check_tickets') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ identifier })
            });

            const data = await response.json();

            if (response.status === 404) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pencarian Selesai',
                    text: data.message
                });
            } else if (data.status === 'empty') {
                Swal.fire({
                    icon: 'info',
                    title: 'Info Event',
                    text: data.message
                });
            } else if (data.status === 'success') {
                showResults(data);
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    function showResults(data) {
        document.getElementById('checkFormContainer').classList.add('hidden');
        document.getElementById('checkResultContainer').classList.remove('hidden');
        document.getElementById('resParticipantName').innerText = data.participant;

        const container = document.getElementById('ticketList');
        container.innerHTML = '';

        data.data.forEach(item => {
            const card = `
                <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl hover:border-blue-200 transition">
                    <div class="font-bold text-gray-800">${item.event_name}</div>
                    <div class="text-xs text-gray-500 mb-3">${item.event_date}</div>
                    <a href="${item.ticket_url}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2 bg-white border border-blue-600 text-blue-600 rounded-lg text-sm font-bold hover:bg-blue-600 hover:text-white transition gap-2">
                        <span class="material-icons text-sm">qr_code</span>
                        Lihat Tiket (QR)
                    </a>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });
    }
</script>

<script>
    // Certificate Logic
    function openCheckCertificateModal() {
        document.getElementById('checkCertificateModal').classList.remove('hidden');
        resetCheckCertModal();
    }

    function closeCheckCertificateModal() {
        document.getElementById('checkCertificateModal').classList.add('hidden');
    }

    function resetCheckCertModal() {
        document.getElementById('checkCertFormContainer').classList.remove('hidden');
        document.getElementById('checkCertResultContainer').classList.add('hidden');
        document.getElementById('lookupCertIdentifier').value = '';
        document.getElementById('certList').innerHTML = '';
    }

    async function performCertificateLookup() {
        const identifier = document.getElementById('lookupCertIdentifier').value;
        if(!identifier) return;

        const btn = document.getElementById('btnCertLookup');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Mencari...';

        try {
            const response = await fetch("{{ route('certificates.search') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email_or_phone: identifier })
            });

            const data = await response.json();

            if (response.status === 404 || data.status === 404) {
                 Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ditemukan',
                    text: data.message || 'Peserta tidak ditemukan.'
                });
            } else if (data.status === 'empty') {
                Swal.fire({
                    icon: 'info',
                    title: 'Belum Ada Sertifikat',
                    text: data.message
                });
            } else if (data.status === 'success') {
                showCertResults(data);
            } else if (data.status === 'error') {
                 Swal.fire('Info', data.message, 'warning');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    function showCertResults(data) {
        document.getElementById('checkCertFormContainer').classList.add('hidden');
        document.getElementById('checkCertResultContainer').classList.remove('hidden');
        document.getElementById('resCertParticipantName').innerText = data.participant.name;

        const container = document.getElementById('certList');
        container.innerHTML = '';

        data.events.forEach(item => {
            // Laravel defaults to snake_case for JSON relations (qrToken -> qr_token)
            const token = item.qr_token ? item.qr_token.token : ''; 
            
            if (!token) return; // Should not happen if attended, but safety check

            const card = `
                <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl hover:border-green-200 transition">
                    <div class="font-bold text-gray-800">${item.event.name}</div>
                    <div class="text-xs text-gray-500 mb-3">${new Date(item.event.start_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}</div>
                    
                    <a href="/certificates/${item.event.slug}/${token}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-white border border-green-600 text-green-600 rounded-lg text-sm font-bold hover:bg-green-600 hover:text-white transition gap-2">
                        <span class="material-icons text-sm">visibility</span>
                        Lihat Sertifikat
                    </a>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });
    }
</script>
@endpush
@endsection
