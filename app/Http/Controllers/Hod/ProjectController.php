<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')->get();

        return view('hod.project', compact('projects'));
    }
    // app/Http/Controllers/Hod/ProjectController.php

    public function show($id)
    {
        $project = Project::with('projectDocuments')->findOrFail($id);
        return view('hod.projects.show', compact('project'));
    }
}
