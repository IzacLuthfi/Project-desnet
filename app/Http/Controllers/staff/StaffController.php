<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class StaffController extends Controller
{
    public function dashboard()
    {
        // Ambil semua project beserta personelnya
        $projects = Project::with('projectPersonel')->get();

        // Kirim ke view
        return view('staff.dashboard', compact('projects'));
    }
}