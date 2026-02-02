<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Participant;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Basic Stats
        $totalEvents = Event::count();
        $activeEvents = Event::where('status', 'active')->count();
        $totalParticipants = Participant::count();
        $attendanceToday = Attendance::whereDate('attendance_date', $today)->count();

        // 2. Weekly Trend (Last 7 Days)
        $last7Days = collect([]);
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $last7Days->put($date, 0);
        }

        $attendanceTrendArr = Attendance::where('attendance_date', '>=', Carbon::today()->subDays(6))
            ->select('attendance_date', \DB::raw('count(*) as count'))
            ->groupBy('attendance_date')
            ->pluck('count', 'attendance_date');

        $attendanceTrend = $last7Days->merge($attendanceTrendArr);

        // 3. Recent Registrations
        $recentParticipants = EventParticipant::with(['participant', 'event', 'participantType'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Active Events Progress
        $activeEventsData = Event::where('status', 'active')
            ->withCount(['eventParticipants'])
            ->get()
            ->map(function($ev) {
                $ev->present_count = $ev->eventParticipants()->whereHas('attendances')->count();
                return $ev;
            });

        return view('admin.dashboard', compact(
            'totalEvents',
            'activeEvents',
            'totalParticipants',
            'attendanceToday',
            'attendanceTrend',
            'recentParticipants',
            'activeEventsData'
        ));
    }
}
