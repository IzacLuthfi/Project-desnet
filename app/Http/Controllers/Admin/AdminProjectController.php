<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminProjectController extends Controller
{
    public function show($id)
    {
        $project = Project::with('projectDocuments')->findOrFail($id);
        return view('admin.project-detail', compact('project'));
    }
}
