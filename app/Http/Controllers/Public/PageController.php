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
        $events = \Illuminate\Support\Facades\Cache::remember('home_active_events', 1800, function () {
            return Event::where('status', 'active')
                        ->where('end_date', '>=', now())
                        ->latest('start_date')
                        ->get();
        });
                
        return view('pages.welcome', compact('events'));
    }

    /**
     * Show active events list with filter.
     */
    public function events(Request $request)
    {
        $activeEventUserIds = \Illuminate\Support\Facades\Cache::remember('active_organizer_ids', 1800, function () {
            return Event::where('status', 'active')
                ->where('end_date', '>=', now())
                ->pluck('user_id')
                ->unique();
        });

        $organizers = \Illuminate\Support\Facades\Cache::remember('active_organizers', 1800, function () use ($activeEventUserIds) {
            return \App\Models\User::whereIn('id', $activeEventUserIds)
                ->orderBy('name')
                ->get();
        });

        // Untuk halaman pencarian/filter, kita menggunakan query string hash sebagai cache key unik
        $cacheKey = 'events_page_' . md5(serialize($request->all()));
        
        $events = \Illuminate\Support\Facades\Cache::remember($cacheKey, 1800, function () use ($request) {
            $query = Event::where('status', 'active')->where('end_date', '>=', now());

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }

            if ($request->filled('organizer')) {
                $query->where('user_id', $request->organizer);
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('has_certificate')) {
                $query->whereHas('certificateTemplate');
            }

            return $query->latest('start_date')->paginate(12);
        });
        
        return view('pages.events', compact('events', 'organizers'));
    }

    /**
     * Show check portal page.
     */
    public function checkPortal()
    {
        return view('pages.check');
    }
}
