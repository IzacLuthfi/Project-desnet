<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class KomisiController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')->get();

        return view('hod.komisi', compact('projects'));
    }
    public function show($project_id)
    {
        $project = Project::with([
            'komisi.projectPersonel.user'
        ])->findOrFail($project_id);

        return view('hod.komisi_detail', compact('project'));
    }
}
