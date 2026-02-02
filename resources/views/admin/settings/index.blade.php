@extends('layouts.app')
@section('title', 'Pengaturan - ' . setting('app_name'))

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Aplikasi</h1>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- General Info --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 flex items-center gap-2">
                    <span class="material-icons text-red-600">settings</span>
                    Informasi Umum
                </h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ setting('app_name') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Aplikasi</label>
                    <textarea name="app_description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">{{ setting('app_description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                    <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        <option value="Asia/Jayapura" {{ setting('timezone') == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                        <option value="Asia/Makassar" {{ setting('timezone') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                        <option value="Asia/Jakarta" {{ setting('timezone') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                        <option value="UTC" {{ setting('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Footer Text</label>
                    <input type="text" name="footer_text" value="{{ setting('footer_text') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                </div>
            </div>

            {{-- Assets & Contact --}}
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 flex items-center gap-2">
                        <span class="material-icons text-red-600">image</span>
                        Logo & Branding
                    </h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo Aplikasi</label>
                            <input type="file" name="app_logo" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            <p class="text-[10px] text-gray-400 mt-1">Format: PNG, JPG (Max 2MB)</p>
                        </div>
                        <div class="flex items-center justify-center p-2 bg-gray-50 rounded-xl border border-dashed">
                             <img src="{{ asset('assets/images/logo/' . setting('app_logo')) }}" class="h-12 object-contain">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Favicon</label>
                            <input type="file" name="app_favicon" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                            <p class="text-[10px] text-gray-400 mt-1">Format: PNG (Max 1MB)</p>
                        </div>
                        <div class="flex items-center justify-center p-2 bg-gray-50 rounded-xl border border-dashed">
                             <img src="{{ asset(setting('app_favicon')) }}" class="h-12 object-contain">
                        </div>
                    </div>
                </div>


                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 flex items-center gap-2">
                        <span class="material-icons text-red-600">contact_support</span>
                        Kontak Support
                    </h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Support</label>
                        <input type="email" name="contact_email" value="{{ setting('contact_email') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp (62xxx)</label>
                        <input type="text" name="contact_whatsapp" value="{{ setting('contact_whatsapp') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="cursor-pointer bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg shadow-red-200 transition-all flex items-center gap-2">
                <span class="material-icons">save</span>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
