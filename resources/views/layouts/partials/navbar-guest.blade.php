<div class="bg-red-600 shadow-md sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <x-app-logo size="w-8" />
            <span class="font-bold text-lg text-white">{{ setting('app_name') }}</span>
        </a>
        
        <div class="flex items-center gap-3 md:gap-6 relative">
            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-white hover:text-orange-200 flex items-center gap-1 text-sm font-medium transition">
                    <span class="material-icons text-sm">home</span> <span>Beranda</span>
                </a>
                <a href="{{ route('events.index') }}" class="text-white hover:text-orange-200 flex items-center gap-1 text-sm font-medium transition">
                    <span class="material-icons text-sm">event</span> <span>Event Aktif</span>
                </a>
                <a href="{{ route('portal.check') }}" class="text-white hover:text-orange-200 flex items-center gap-1 text-sm font-medium transition">
                    <span class="material-icons text-sm">confirmation_number</span> <span>Cek Tiket & Sertifikat</span>
                </a>
                <a href="{{ route('register') }}" class="text-white hover:text-orange-200 flex items-center gap-1 text-sm font-medium transition">
                    <span class="material-icons text-sm">person_add</span> <span>Daftar Akun</span>
                </a>
            </div>

            <!-- Login / Dashboard Button (Always Visible, Icon Only) -->
            @if(Auth::guard('admin')->check())
                <a href="{{ route('admin.dashboard') }}" class="bg-white text-red-600 hover:bg-gray-100 w-9 h-9 rounded-full flex items-center justify-center transition shadow-sm" title="Dashboard">
                    <span class="material-icons text-[20px]">dashboard</span>
                </a>
            @else
                <a href="{{ route('admin.login') }}" class="bg-white text-red-600 hover:bg-gray-100 w-9 h-9 rounded-full flex items-center justify-center transition shadow-sm" title="Login Admin">
                    <span class="material-icons text-[20px]">login</span>
                </a>
            @endif

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white hover:text-orange-200 focus:outline-none flex items-center justify-center w-9 h-9 transition">
                <span class="material-icons">menu</span>
            </button>
            
            <!-- Navigation Links (Mobile Dropdown) -->
            <div id="nav-links-mobile" class="hidden absolute top-full right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-50 md:hidden flex-col border border-gray-100 origin-top-right">
                <a href="{{ route('home') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-red-600 flex items-center gap-3 transition">
                    <span class="material-icons text-lg text-gray-400">home</span> Beranda
                </a>
                <a href="{{ route('events.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-red-600 flex items-center gap-3 transition">
                    <span class="material-icons text-lg text-gray-400">event</span> Event Aktif
                </a>
                <a href="{{ route('portal.check') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-red-600 flex items-center gap-3 transition">
                    <span class="material-icons text-lg text-gray-400">confirmation_number</span> Cek Tiket & Sertifikat
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-red-600 flex items-center gap-3 transition border-t border-gray-100 mt-1 pt-3">
                    <span class="material-icons text-lg text-gray-400">person_add</span> Daftar Akun
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('nav-links-mobile');
        
        if(btn && menu) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
                menu.classList.toggle('flex');
            });

            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.add('hidden');
                    menu.classList.remove('flex');
                }
            });
        }
    });
</script>