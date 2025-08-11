<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCommission;
use Illuminate\Http\Request;

class ProjectCommissionController extends Controller
{
    public function edit($projectId)
    {
        $project = Project::with('projectPersonel')->findOrFail($projectId);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, $projectId)
    {
        $request->validate([
            'commission' => 'required|array',
            'commission.*.user_id' => 'required|exists:users,id',
            'commission.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->commission as $data) {
            ProjectCommission::updateOrCreate(
                [
                    'project_id' => $projectId,
                    'user_id' => $data['user_id']
                ],
                [
                    'percentage' => $data['percentage']
                ]
            );
        }

        return redirect()->route('projects.index')->with('success', 'Komisi berhasil diperbarui.');
    }
}