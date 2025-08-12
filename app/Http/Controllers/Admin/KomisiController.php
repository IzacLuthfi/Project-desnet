<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
class KomisiController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')->get();
        return view('admin.komisi.index' , compact('projects')); // arahkan ke resources/views/admin/komisi/index.blade.php

    }
    public function show($project_id)
    {
        $project = Project::with([
            'komisi.projectPersonel.user'
        ])->findOrFail($project_id);

        return view('admin.komisi_detail', compact('project'));
    }
}
