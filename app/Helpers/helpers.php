<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        static $settings;

        if (!$settings) {
            $settings = \Illuminate\Support\Facades\Cache::remember('app_settings', 3600, function () {
                return \App\Models\Setting::pluck('value', 'key')->toArray();
            });
        }

        return $settings[$key] ?? $default;
    }
}

if (! function_exists('isActive')) {
    function isActive($route)
    {
        return request()->routeIs($route)
            ? 'bg-red-50 text-red-600 font-semibold'
            : 'text-gray-700 hover:bg-gray-100';
    }
}

if (! function_exists('eventRegisterUrl')) {
    function eventRegisterUrl($event)
    {
        return route('event.register', $event->slug);
    }
}
