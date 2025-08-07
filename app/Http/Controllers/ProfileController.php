<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed', // validasi konfirmasi password
        ]);

        // Jika password lama diisi, validasi kebenarannya
        if ($request->filled('old_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah.'
                ]);
            }

            // Jika password baru juga diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;

        /** @var \App\Models\User $user */
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'badge' => $user->role === 'Admin' ? 'bg-primary' : 'bg-secondary'
        ]);
    }
}
