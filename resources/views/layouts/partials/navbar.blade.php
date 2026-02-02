<nav class="fixed top-0 z-50 w-full bg-linear-to-r from-red-500 to-red-800 border-b border-gray-200 h-14">
    <div class="h-full px-4 flex justify-between items-center">

        <!-- Left -->
        <div class="flex items-center gap-2">
            <button id="toggleLabel"
                class="hidden md:block p-2 text-white rounded hover:bg-red-600">
                <x-icon name="menu_open" size="24px"/>
            </button>
            <button id="toggleSidebar"
                class="block md:hidden p-2 text-white hover:bg-white/10 rounded">
                <x-icon name="menu" />
            </button>
            <x-app-logo size="w-10" />
            <span class="text-base font-semibold text-white">
                {{ setting('app_name') }}
            </span>
        </div>

        <!-- Right -->
        <div class="relative">
            <button data-dropdown-toggle="dropdown-user"
                class="flex items-center text-white p-2 rounded hover:bg-red-600">
                <x-icon name="account_circle" />
            </button>

            <div id="dropdown-user"
                class="hidden absolute right-0 mt-2 w-48 bg-white rounded shadow border">
                <div class="px-4 py-2 text-sm">
                    <div class="font-medium">{{ auth('admin')->user()->name }}</div>
                    <div class="text-gray-500 text-xs">
                        {{ auth('admin')->user()->email }} ({{ auth('admin')->user()->role }})
                    </div>
                </div>
                <div class="px-2 pb-2">
                    <a href="{{ route('admin.profile.password') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded">
                        <span class="material-icons text-sm mr-2">lock</span>
                        Ganti Password
                    </a>
                </div>
                <hr class="border-gray-100">
                <form method="POST" action="{{ route('admin.logout') }}" class="p-2">
                    @csrf
                    <button class="cursor-pointer w-full text-left px-4 py-2 rounded text-sm hover:bg-red-50 hover:text-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>
