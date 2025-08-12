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
    public function verifikasiAjax($id)
    {
        $project = \App\Models\Project::findOrFail($id);
        $project->status_komisi = 'Disetujui';
        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Komisi berhasil diverifikasi',
            'status_komisi' => $project->status_komisi
        ]);
    }
    public function batalkanVerifikasiAjax($id)
    {
        $project = \App\Models\Project::findOrFail($id);
        $project->status_komisi = 'Belum Disetujui';
        $project->save();

        return response()->json([
            'success' => true,
            'status' => 'Belum Disetujui',
            'message' => 'Verifikasi komisi dibatalkan'
        ]);
    }
}
