@extends('layouts.guest')

@section('title', 'Pendaftaran Akun - ' . setting('app_name'))

@section('content')
<div class="w-full max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border-t-4 border-red-600 overflow-hidden">
        <div class="p-6 md:p-8">
            <div class="text-center mb-8">
                <x-app-logo size="w-16 mx-auto mb-4" />
                <h2 class="text-2xl font-bold text-gray-800">Daftar Akun Baru</h2>
                <p class="text-sm text-gray-500 mt-2">Daftar sebagai Penyelenggara Event</p>
            </div>

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input id="name" name="name" label="Nama Lengkap" type="text" :value="old('name')" required autofocus />
                </div>

                <div>
                    <x-input id="email" name="email" label="Email" type="email" :value="old('email')" required />
                </div>

                <div>
                    <x-input id="password" name="password" label="Password" type="password" required />
                </div>

                <div>
                    <x-input id="password_confirmation" name="password_confirmation" label="Konfirmasi Password" type="password" required />
                </div>

                <div class="pt-2">
                    <x-button type="submit" variant="primary" class="w-full justify-center">
                        Daftar Sekarang
                    </x-button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                Sudah punya akun? <a href="{{ route('admin.login') }}" class="text-red-600 font-bold hover:underline">Login di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
