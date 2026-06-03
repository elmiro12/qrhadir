<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_ADMIN_EVENT,
            'status' => 'inactive',
        ]);

        return redirect()->route('admin.login')->with('swal', [
            'icon' => 'success',
            'title' => 'Registrasi Berhasil',
            'message' => 'Akun Anda berhasil didaftarkan. Namun status Anda masih belum aktif. Silakan hubungi Super Admin untuk aktivasi.',
        ]);
    }
}
