<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Komisi; // pastikan ini mengarah ke model Komisi yang sudah diubah tabelnya
use Illuminate\Support\Facades\Auth;

class KomisiiController extends Controller
{
    public function index()
    {
        $projects = Project::with([
            'projectPersonel.user',
            'komisi.projectPersonel.user'
        ])->get();

        return view('pm.komisi', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'margin' => 'required|numeric|min:0',
            'komisi' => 'required|array',
            'komisi.*' => 'required|numeric|min:0|max:100'
        ]);

        foreach ($request->komisi as $personelId => $persentase) {
            $nilaiKomisi = ($request->margin * $persentase) / 100;

            Komisi::create([
                'project_id' => $request->project_id,
                'project_personel_id' => $personelId,
                'margin' => $request->margin,
                'persentase' => $persentase,
                'nilai_komisi' => $nilaiKomisi
            ]);
        }

        return redirect()->back()->with('success', 'Komisi berhasil disimpan.');
    }
    
    public function show($project_id)
    {
        $project = Project::with([
            'komisi.projectPersonel.user'
        ])->findOrFail($project_id);

        return view('pm.komisi_detail', compact('project'));
    }
}
