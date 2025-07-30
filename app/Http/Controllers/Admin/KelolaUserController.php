<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class KelolaUserController extends Controller
{
    // Tampilkan semua user dengan pagination
    // KelolaUserController.php
public function index()
{
    ini_set('memory_limit', '1024M'); // Harus di atas!

    $users = User::select('id', 'name', 'email', 'role', 'is_active')->paginate(10); 
    return view('admin.kelola-user.index', compact('users'));
}


    // Tampilkan form tambah user
    public function create()
    {
        return view('admin.kelola-user.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'role'     => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => $validated['role'],
            'password'  => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return redirect()->route('kelola-user.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Tampilkan form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.kelola-user.edit', compact('user'));
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('kelola-user.index')->with('success', 'User berhasil dihapus.');
    }
}
