<?php

namespace App\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectPMController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')->get();

        return view('pm.project', compact('projects'));
    }
}