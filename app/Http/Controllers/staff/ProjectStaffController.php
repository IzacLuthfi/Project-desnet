<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectStaffController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')
            ->whereHas('projectPersonel', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        return view('staff.project', compact('projects'));
    }

    public function projectPersonel()
    {
        return $this->hasMany(ProjectPersonel::class, 'project_id', 'id');
    }
    
    public function show($id)
    {
        $project = Project::with('projectDocuments')->findOrFail($id);
        return view('staff.project.show', compact('project'));
    }
}