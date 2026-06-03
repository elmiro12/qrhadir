<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Show the home page with active events.
     */
    public function home()
    {
        $events = Event::where('status', 'active')
                    ->where('end_date', '>=', now())
                    ->latest('start_date')
                    ->get();
                
        return view('pages.welcome', compact('events'));
    }

    /**
     * Show active events list with filter.
     */
    public function events(Request $request)
    {
        $query = Event::where('status', 'active')->where('end_date', '>=', now());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('organizer')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->organizer}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('has_certificate')) {
            $query->whereHas('certificateTemplate');
        }

        $events = $query->latest('start_date')->paginate(12);
        
        return view('pages.events', compact('events'));
    }

    /**
     * Show check portal page.
     */
    public function checkPortal()
    {
        return view('pages.check');
    }
}
