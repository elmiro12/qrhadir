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
}
