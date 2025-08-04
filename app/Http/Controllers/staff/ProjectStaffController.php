<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectStaffController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')->get();

        return view('staff.project', compact('projects'));
    }
}