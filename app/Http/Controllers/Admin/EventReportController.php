<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Attendance;
use App\Models\EventParticipant;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class EventReportController extends Controller
{
    /**
     * Show report dashboard for an event
     */
    public function show(Request $request, Event $event)
    {
        if ($event->status === 'draft') {
            return redirect()->route('admin.events.index')->with('error', 'Laporan belum tersedia untuk event draft.');
        }

        // 1. Stats (Always for full event)
        $totalParticipants = $event->eventParticipants()->count();
        
        // List of participants for stats calculation (all of them)
        $allParticipants = $event->eventParticipants()->with(['attendances'])->get();
        
        $presentCount = $allParticipants->filter(function($ep) {
            return $ep->attendances->isNotEmpty();
        })->count();
        
        $absentCount = $totalParticipants - $presentCount;
        
        // 2. Participants without Pagination and Search (handled by Simple Datatables)
        $participants = $event->eventParticipants()
                    ->join('participant_types', 'event_participants.participant_type_id', '=', 'participant_types.id')
                    ->join('participants', 'event_participants.participant_id', '=', 'participants.id')
                    ->with(['participant', 'attendances', 'participantType'])
                    ->orderBy('participant_types.id', 'asc')
                    ->orderBy('participants.name', 'asc')
                    ->select('event_participants.*')
                    ->get();

        // Per-day columns2
        $dates = [];
        $start = \Carbon\Carbon::parse($event->start_date);
        $end = \Carbon\Carbon::parse($event->end_date);
        $period = \Carbon\CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return view('admin.reports.show', compact('event', 'participants', 'totalParticipants', 'presentCount', 'absentCount', 'dates'));
    }

    /**
     * Export to Excel
     */
    public function export(Event $event) 
    {
        if ($event->status === 'draft') {
            return redirect()->route('admin.events.index')->with('error', 'Export belum tersedia untuk event draft.');
        }
        return Excel::download(new AttendanceExport($event), 'Laporan_Event_' . $event->slug . '.xlsx');
    }

    /**
     * Print View (PDF style)
     */
    public function print(Event $event)
    {
        if ($event->status === 'draft') {
            return redirect()->route('admin.events.index')->with('error', 'Cetak belum tersedia untuk event draft.');
        }
        // Same data as Show
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

        return view('admin.reports.print', compact('event', 'participants', 'dates'));
    }
}
