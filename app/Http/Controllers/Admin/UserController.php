<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of admin_event users.
     */
    public function index()
    {
        $users = User::where('role', User::ROLE_ADMIN_EVENT)->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created admin_event user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_ADMIN_EVENT,
        ]);

        return back()->with('success', 'Akun Admin Event berhasil dibuat.');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Safety check to ensure we only manage admin_event
        if ($user->role !== User::ROLE_ADMIN_EVENT) {
            abort(403, 'Anda tidak diizinkan mengubah akun ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * Reset password for the specified user.
     */
    public function resetPassword(User $user)
    {
        // Safety check
        if ($user->role !== User::ROLE_ADMIN_EVENT) {
            abort(403, 'Anda tidak diizinkan mereset akun ini.');
        }

        $user->update([
            'password' => Hash::make('password123'),
        ]);

        return back()->with('success', 'Password akun ' . $user->name . ' telah direset menjadi: password123');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->role !== User::ROLE_ADMIN_EVENT) {
            abort(403, 'Anda tidak diizinkan menghapus akun ini.');
        }

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
    }
}
