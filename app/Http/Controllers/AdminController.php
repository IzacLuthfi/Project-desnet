<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Mengambil semua data proyek beserta personelnya (relasi hasMany)
        $projects = Project::with('projectPersonel')->get();

        return view('admin.dashboard', compact('projects'));
    }
}
