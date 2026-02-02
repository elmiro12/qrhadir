@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-icons text-orange-500">lock</span>
                Keamanan Akun
            </h2>

            <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-4">
                    <x-input 
                        type="password" 
                        name="current_password" 
                        label="Password Saat Ini" 
                        required 
                    />

                    <hr class="border-gray-100">

                    <x-input 
                        type="password" 
                        name="password" 
                        label="Password Baru" 
                        required 
                    />

                    <x-input 
                        type="password" 
                        name="password_confirmation" 
                        label="Konfirmasi Password Baru" 
                        required 
                    />
                </div>

                <div class="pt-4 flex justify-end">
                    <x-button type="submit" variant="secondary" icon="save">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-6 p-4 bg-orange-50 rounded-xl border border-orange-100 flex gap-3 italic text-sm text-orange-700">
        <span class="material-icons">info</span>
        <p>Gunakan kombinasi password yang kuat dan unik untuk menjaga keamanan akun Anda.</p>
    </div>
</div>
@endsection
