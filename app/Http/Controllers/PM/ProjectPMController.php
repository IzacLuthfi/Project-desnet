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
    public function storeDocument(Request $request, Project $project)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string',
            'dokumen' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg',
            'keterangan' => 'nullable|string',
        ]);

        $path = $request->file('dokumen')->store('dokumen_proyek');

        $project->dokumen()->create([
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'file_path' => $path,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil ditambahkan');
    }
    // app/Http/Controllers/ProjectController.php

}
