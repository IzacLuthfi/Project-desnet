<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|in:admin,hod,pm,staff',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $validated['role'],
    ]);

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
    ]);
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
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus'], 200);
    }
    
   public function search(Request $request)
{
    $query = $request->get('q');

    $users = User::where('name', 'LIKE', "%{$query}%")
        ->orWhere('email', 'LIKE', "%{$query}%")
        ->get(); // ⚡ pakai get() bukan paginate()

    return response()->json([
        'data' => $users
    ]);
}



    // Proses update user
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required',
    ];

    // Kalau user mau ubah password
    if ($request->filled('password')) {
        $rules['old_password'] = 'required';
        $rules['password'] = 'required|min:6|confirmed'; // pakai password_confirmation
    }

    $request->validate($rules);

    // Cek old password kalau mau ganti password
    if ($request->filled('password')) {
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'errors' => ['old_password' => ['Password lama salah']]
            ], 422);
        }
        $user->password = Hash::make($request->password);
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->save();

    return response()->json($user);
}

}
