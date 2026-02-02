@extends('layouts.login')

@section('title', 'Login Admin')

@section('content')
<div class="mb-4">
        <a href="{{ route('home') }}" class="inline-flex items-center text-gray-500 hover:text-orange-600 transition text-sm">
            <span class="material-icons text-base mr-1">arrow_back</span>
            Kembali
        </a>
</div>
<h2 class="text-2xl font-bold mb-1">
    Login Admin
</h2>

<p class="text-amber-700 mb-6">
    Silakan masuk menggunakan akun admin
</p>

<form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
    @csrf

    <div>
        <x-input type="email" name="email" label='Email' class="bg-white p-2 text-black" required />
    </div>

    <div>
       <x-input type="password" name="password" label='Password' class="bg-white p-2 text-black" required />
    </div>

    <x-button type="submit" variant="secondary" icon="login">Login</x-button>
</form>
@endsection
