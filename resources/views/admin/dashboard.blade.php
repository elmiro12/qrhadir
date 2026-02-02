@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-desc', 'Ringkasan aktivitas sistem absensi saat ini')

@section('css')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
{{-- 1. Stat Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-card-icon title="Total Event" icon="event" variant="info">
        {{ $totalEvents }}
    </x-card-icon>

    <x-card-icon title="Event Aktif" icon="check_circle" variant="success">
        {{ $activeEvents }}
    </x-card-icon>

    <x-card-icon title="Total Peserta" icon="groups" variant="primary">
        {{ $totalParticipants }}
    </x-card-icon>

    <x-card-icon title="Hadir Hari Ini" icon="how_to_reg" variant="dark">
        {{ $attendanceToday }}
    </x-card-icon>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    {{-- 2. Weekly Trend Chart --}}
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Tren Kehadiran (7 Hari Terakhir)</h3>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Update Otomatis</span>
        </div>
        <div class="relative h-[300px] w-full">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    {{-- 3. Recent Registrations --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Pendaftar Terbaru</h3>
        <div class="flow-root">
            <ul role="list" class="-my-5 divide-y divide-gray-100">
                @forelse($recentParticipants as $rp)
                    <li class="py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                                    <span class="material-icons">person</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $rp->participant->name }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $rp->event->name }}
                                </p>
                                <p class="text-xs text-gray-400 truncate italic">
                                    {{ $rp->registered_via === 'admin' ? 'Didaftarkan oleh Administrator' : 'Mendaftar via Portal' }}
                                </p>
                            </div>
                            <div>
                                <x-badge variant="info" class="text-[10px]">{{ $rp->participantType->name ?? 'User' }}</x-badge>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-4 text-center text-gray-400 text-sm">Belum ada aktivitas</li>
                @endforelse
            </ul>
        </div>
        <div class="mt-6">
            <x-link-button href="{{ route('admin.participants.index') }}" variant="secondary" size="sm" class="w-full text-center">
                Lihat Semua Peserta
            </x-link-button>
        </div>
    </div>
</div>

{{-- 4. Active Events Highlight --}}
@if($activeEventsData->count() > 0)
<div class="mb-4">
    <h3 class="text-lg font-bold text-gray-800 mb-4 uppercase tracking-wider text-sm">Progres Event Aktif</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($activeEventsData as $ev)
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-bold text-gray-800">{{ $ev->name }}</h4>
                        <x-badge variant="success" size="xs">ACTIVE</x-badge>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center mb-4">
                        <span class="material-icons text-xs mr-1">location_on</span> {{ $ev->location ?? 'Online' }}
                    </p>
                </div>
                
                @php
                    $percent = $ev->event_participants_count > 0 ? round(($ev->present_count / $ev->event_participants_count) * 100) : 0;
                @endphp
                
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-medium">
                        <span class="text-gray-600">Kehadiran: {{ $ev->present_count }} / {{ $ev->event_participants_count }}</span>
                        <span class="text-orange-600">{{ $percent }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <x-link-button href="{{ route('admin.events.participants.index', $ev) }}" variant="primary" size="xs" class="flex-1 text-center">
                        Kelola
                    </x-link-button>
                    <x-link-button href="{{ route('admin.reports.show', $ev) }}" variant="secondary" size="xs" icon="assessment" title="Laporan" />
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('attendanceChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        const labels = @json($attendanceTrend->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values());
        const rowData = @json($attendanceTrend->values());

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kehadiran',
                    data: rowData,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f97316',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 10,
                        ticks: {
                            stepSize: 1,
                            font: { size: 10 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
