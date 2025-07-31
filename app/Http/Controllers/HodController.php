<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class HodController extends Controller
{
    public function dashboard()
    {
        // Ambil semua project beserta personelnya
        $projects = Project::with('projectPersonel')->get();

        // Kirim ke view
        return view('hod.dashboard', compact('projects'));
    }
}
