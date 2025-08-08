<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // ⬅️ Tambahkan ini untuk akses model User

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil daftar user berdasarkan role
        $pms = User::where('role', 'pm')->get();
        $staffs = User::where('role', 'staff')->get();

        // Kirim data ke view
        return view('admin.dashboard', compact('pms', 'staffs'));
    }
}
