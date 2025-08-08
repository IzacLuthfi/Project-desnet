<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $projects = Project::with('projectPersonel')->get();
        $projectManagers = User::where('role', 'pm')->get();
        $staffs = User::where('role', 'staff')->get();

        return view('admin.dashboard', compact('projects', 'projectManagers', 'staffs'));
    }
}
