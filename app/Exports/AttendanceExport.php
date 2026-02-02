<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function view(): View
    {
        $event = $this->event;
        $participants = $event->eventParticipants()
                    ->join('participant_types', 'event_participants.participant_type_id', '=', 'participant_types.id')
                    ->join('participants', 'event_participants.participant_id', '=', 'participants.id')
                    ->with(['participant', 'attendances', 'participantType'])
                    ->orderBy('participant_types.id', 'asc')
                    ->orderBy('participants.name', 'asc')
                    ->select('event_participants.*')
                    ->get();
                    
        $dates = [];
        $start = \Carbon\Carbon::parse($event->start_date);
        $end = \Carbon\Carbon::parse($event->end_date);
        $period = \Carbon\CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return view('admin.reports.excel', compact('event', 'participants', 'dates'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            3    => ['font' => ['bold' => true]],
        ];
    }
}
