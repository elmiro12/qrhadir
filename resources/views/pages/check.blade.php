@extends('layouts.guest')

@section('title', 'Portal Cek Tiket & Sertifikat - ' . setting('app_name'))

@section('content')
<div class="w-full max-w-5xl mx-auto">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900">Portal Pelayanan Peserta</h2>
        <p class="text-gray-500 mt-2">Cek tiket event Anda atau unduh e-sertifikat yang telah terbit.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Card Cek Tiket --}}
        <div class="bg-white rounded-2xl shadow-md border-t-4 border-blue-500 overflow-hidden flex flex-col h-full">
            <div class="p-6 md:p-8 flex-1">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="material-icons text-3xl">confirmation_number</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Cek Tiket Saya</h3>
                <p class="text-sm text-gray-500 mb-6">Masukkan Email atau Nomor HP yang Anda gunakan saat mendaftar event untuk melihat QR Code tiket Anda.</p>
                
                <div id="checkFormContainer">
                    <div class="space-y-4">
                        <x-input id="lookupIdentifier" name="identifier" label="Email / No HP" placeholder="Contoh: 08123... atau email@mail.com" required />
                        <x-button onclick="performTicketLookup()" variant="primary" class="w-full justify-center !bg-blue-600 hover:!bg-blue-700" size="md" id="btnLookup">
                            Cari Tiket
                        </x-button>
                    </div>
                </div>

                {{-- Result List --}}
                <div id="checkResultContainer" class="hidden space-y-4 mt-4">
                    <div class="flex items-center gap-3 p-3 bg-blue-50 text-blue-800 rounded-xl border border-blue-100">
                        <span class="material-icons">person</span>
                        <div>
                            <div class="text-xs text-blue-600">Terdaftar sebagai:</div>
                            <div class="font-bold" id="resParticipantName">-</div>
                        </div>
                    </div>

                    <div class="max-h-64 overflow-y-auto space-y-3" id="ticketList">
                        {{-- Loop data via JS --}}
                    </div>

                    <button onclick="resetCheckModal()" class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition mt-4">
                        Cari identitas lain
                    </button>
                </div>
            </div>
        </div>

        {{-- Card Cek Sertifikat --}}
        <div class="bg-white rounded-2xl shadow-md border-t-4 border-green-500 overflow-hidden flex flex-col h-full">
            <div class="p-6 md:p-8 flex-1">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="material-icons text-3xl">workspace_premium</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Unduh E-Sertifikat</h3>
                <p class="text-sm text-gray-500 mb-6">Masukkan Email atau Nomor HP untuk mencari dan mengunduh sertifikat dari event yang telah Anda ikuti.</p>
                
                <div id="checkCertFormContainer">
                    <div class="space-y-4">
                        <x-input id="lookupCertIdentifier" name="identifier" label="Email / No HP" placeholder="Contoh: 08123... atau email@mail.com" required />
                        <x-button onclick="performCertificateLookup()" variant="success" class="w-full justify-center" size="md" id="btnCertLookup">
                            Cari Sertifikat
                        </x-button>
                    </div>
                </div>

                {{-- Result List --}}
                <div id="checkCertResultContainer" class="hidden space-y-4 mt-4">
                    <div class="flex items-center gap-3 p-3 bg-green-50 text-green-800 rounded-xl border border-green-100">
                        <span class="material-icons">person</span>
                        <div>
                            <div class="text-xs text-green-600">Peserta:</div>
                            <div class="font-bold" id="resCertParticipantName">-</div>
                        </div>
                    </div>

                    <div class="max-h-64 overflow-y-auto space-y-3 pr-2" id="certList">
                        {{-- Loop data via JS --}}
                    </div>

                    <button onclick="resetCheckCertModal()" class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition mt-4">
                        Cari identitas lain
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Ticket Logic
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

    // Certificate Logic
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
            const token = item.qr_token ? item.qr_token.token : ''; 
            if (!token) return;

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
