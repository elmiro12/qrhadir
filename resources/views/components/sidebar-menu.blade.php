@props([
    'link' => 'home',           // menu link
    'activeLink' => 'home',     // menu active route
    'activeLink2' => null,      // secondary active route
    'icon' => 'home',             // material icon name, ex: "login"
    'label' => 'left',   // left | right
])
<li {{ $attributes->merge([
        'class' => ""
    ]) }}>
<a href="{{ route($link) }}"
   class="flex items-center gap-3 p-2 rounded {{ (request()->routeIs($activeLink) || ($activeLink2 && request()->routeIs($activeLink2))) ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
    <x-icon name="{{ $icon }}" class="{{ (request()->routeIs($activeLink) || ($activeLink2 && request()->routeIs($activeLink2))) ? 'text-orange-600' : '' }}" />
    <span class="menu-label">{{ $label }}</span>
</a>
</li>