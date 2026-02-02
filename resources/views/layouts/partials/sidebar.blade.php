<aside id="sidebar"
    class="fixed top-14 left-0 z-40 h-[calc(100vh-56px)]
           w-64 bg-linear-to-b from-white border-r border-red-600 shadow-xl to-gray-100 transition-transform
           -translate-x-full sm:translate-x-0">

    <ul class="p-3 space-y-1 text-sm">
        
        <x-sidebar-menu link='home' icon='home' label='Beranda' />
         
        <x-sidebar-menu link='admin.dashboard' activeLink='admin.dashboard' icon='dashboard' label='Dashboard' />
         
        <x-sidebar-menu link='admin.events.index' activeLink='admin.events.*' icon='event' label='Event' />
        
        <x-sidebar-menu link='admin.certificates.list' activeLink='admin.certificates.*' activeLink2='admin.events.certificates.*' icon='workspace_premium' label='Kelola Sertifikat' />

        <x-sidebar-menu link='admin.participants.index' activeLink='admin.participants.*' icon='group' label='Data Peserta' />
        
        <x-sidebar-menu link='admin.attendance.scan' activeLink='admin.attendance.*' icon='qr_code_scanner' label='Scan QR Peserta' />
        
        @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->isSuperAdmin())
            <x-sidebar-menu link='admin.users.index' activeLink='admin.users.*' icon='manage_accounts' label='Manajemen Akun' />
            <x-sidebar-menu link='admin.settings.index' activeLink='admin.settings.index' icon='settings' label='Pengaturan' />
        @endif

        <hr class="border-gray-200 my-2 mx-3">
        <x-sidebar-menu link='admin.profile.password' activeLink='admin.profile.*' icon='lock' label='Ganti Password' />

    </ul>
</aside>
