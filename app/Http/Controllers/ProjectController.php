<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Personel;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('personel')->get();
        return view('project.index', compact('projects'));
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
        ]);

        if ($request->has('personel')) {
            foreach ($request->personel as $person) {
                Personel::create([
                    'project_id' => $project->id,
                    'nama' => $person['nama'],
                    'role' => $person['role'],
                ]);
            }
        }

        return redirect()->route('project.index')->with('success', 'Proyek berhasil disimpan.');
    }

    public function show($id)
    {
        $project = Project::with('personel')->findOrFail($id);
        return view('project.detail', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::with('personel')->findOrFail($id);
        return view('project.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->update([
            'judul' => $request->judul,
            'nilai' => $request->nilai,
            'pm' => $request->pm,
            'status' => $request->status,
        ]);

        Personel::where('project_id', $project->id)->delete();

        if ($request->has('personel')) {
            foreach ($request->personel as $person) {
                Personel::create([
                    'project_id' => $project->id,
                    'nama' => $person['nama'],
                    'role' => $person['role'],
                ]);
            }
        }

        return redirect()->route('project.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->personel()->delete();
        $project->delete();

        return redirect()->route('project.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
