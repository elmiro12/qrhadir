<table>
    <thead>
    <tr>
        <th colspan="{{ count($dates) + 3 }}" style="text-align: center; font-weight: bold; font-size: 18px;">
            LAPORAN ABSENSI EVENT ({{ strtoupper(setting('app_name')) }})
        </th>
    </tr>
    <tr>
        <th colspan="{{ count($dates) + 3 }}" style="text-align: center; font-weight: bold; font-size: 14px;">
            {{ strtoupper($event->name) }}
        </th>
    </tr>
    <tr>
        <th colspan="{{ count($dates) + 3 }}" style="text-align: center;">
            {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d F Y') }}
        </th>
    </tr>
    <tr>
        <th colspan="{{ count($dates) + 3 }}" style="text-align: center; font-size: 10px;font-style:italic">
            URL - {{ url()->current() }}
        </th>
    </tr>
    <tr>
        <th colspan="{{ count($dates) + 3 }}" style="text-align: center; font-size: 10px;font-style:italic">
            
        </th>
    </tr>
    <tr>
        <th style="font-weight: bold; border: 1px solid black; background-color: #cccccc;">Nama Peserta</th>
        <th style="font-weight: bold; border: 1px solid black; background-color: #cccccc;">Tipe</th>
        @foreach($dates as $date)
            <th style="font-weight: bold; border: 1px solid black; background-color: #cccccc; text-align: center;">
                {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
            </th>
        @endforeach
        <th style="font-weight: bold; border: 1px solid black; background-color: #cccccc; text-align: center;">Total Hadir</th>
    </tr>
    </thead>
    <tbody>
    @foreach($participants as $ep)
        <tr>
            <td style="border: 1px solid black;">{{ $ep->participant->name }}</td>
            <td style="border: 1px solid black;">{{ $ep->participantType->name ?? '-' }}</td>
            @php $count = 0; @endphp
            @foreach($dates as $date)
                @php
                    $attn = $ep->attendances->first(function($a) use ($date) {
                        return $a->attendance_date == $date; 
                    });
                    if($attn) $count++;
                @endphp
                <td style="border: 1px solid black; text-align: center;">
                    {{ $attn ? \Carbon\Carbon::parse($attn->checkin_time)->format('H:i') : '-' }}
                </td>
            @endforeach
            <td style="border: 1px solid black; text-align: center;">{{ $count }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
