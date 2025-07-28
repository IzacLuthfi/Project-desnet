<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectPersonel;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')->get();
        return view('project.index', compact('projects'));
    }
   public function ajaxStore(Request $request)
{
    $project = Project::create([
        'judul' => $request->judul,
        'nilai' => $request->nilai,
        'pm' => $request->pm,
    ]);

    foreach ($request->personel as $p) {
        $project->projectPersonel()->create([
            'nama' => $p['nama'],
            'role' => $p['role'],
        ]);
    }

    $project->load('projectPersonel');

    return response()->json([
        'success' => true,
        'message' => 'Proyek berhasil disimpan.',
        'project' => $project,
    ]);
}



    public function dashboard()
    {
        $projects = Project::with('projectPersonel')->get();
        return view('admin.dashboard', compact('projects'));
    }

    public function create()
    {
        return view('project.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'nilai' => 'required|numeric',
        'pm' => 'required|string',
        'personel.*.nama' => 'required|string',
        'personel.*.role' => 'required|string',
    ]);

    $project = Project::create([
        'judul' => $request->judul,
        'nilai' => $request->nilai,
        'pm' => $request->pm,
        'status' => $request->status ?? 'Belum dimulai',
        'status_dokumen' => 'Belum Diajukan', // ✅ Tambahkan default
        'status_komisi' => 'Belum Disetujui',  // ✅ Tambahkan default
    ]);

    if ($request->has('personel')) {
        foreach ($request->personel as $person) {
            ProjectPersonel::create([
                'project_id' => $project->id,
                'nama' => $person['nama'],
                'role' => $person['role'],
            ]);
        }
    }

    return redirect()->route('projects.index')->with('success', 'Proyek berhasil disimpan.');
}


    public function edit($id)
    {
        $project = Project::with('projectPersonel')->findOrFail($id);
        return view('project.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'pm' => 'required|string',
            'status' => 'required|string',
            'personel' => 'array',
            'personel.*' => 'nullable|string',
            'role.*' => 'nullable|string',
        ]);

        $project = Project::findOrFail($id);

        $project->update([
            'judul' => $request->judul,
            'nilai' => $request->nilai,
            'project_manager' => $request->pm,
            'status_dokumen' => $request->status,
        ]);

        // Update personel
        ProjectPersonel::where('project_id', $project->id)->delete();

        if ($request->has('personel') && $request->has('role')) {
            foreach ($request->personel as $i => $nama) {
                if (!empty($nama)) {
                    ProjectPersonel::create([
                        'project_id' => $project->id,
                        'nama' => $nama,
                        'peran' => $request->role[$i] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->projectPersonel()->delete();
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
