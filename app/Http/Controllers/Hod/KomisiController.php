<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectCommission;
use Illuminate\Support\Facades\Auth;

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
            'komisi.projectPersonel.user',
            'komisi.user'
        ])->findOrFail($project_id);

        return view('hod.komisi_detail', compact('project'));
    }
    public function verifikasiAjax($id)
    {
        $project = Project::findOrFail($id);
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
        $project = Project::findOrFail($id);
        $project->status_komisi = 'Belum Disetujui';
        $project->save();

        return response()->json([
            'success' => true,
            'status' => 'Belum Disetujui',
            'message' => 'Verifikasi komisi dibatalkan'
        ]);
    }
    public function totalPerPersonel()
    {
        $komisiSemuaProject = ProjectCommission::with(['projectPersonel.user', 'user'])->get();

        return view('hod.komisi_total', compact('komisiSemuaProject'));
    }

    public function totalPerPersonelBulananTable()
    {
        $komisiSemuaProject = ProjectCommission::with(['projectPersonel.user', 'user'])->get();

        $personelData = [];

        foreach ($komisiSemuaProject as $komisi) {
            $nama = $komisi->user?->name ?? $komisi->projectPersonel?->user?->name ?? '-';
            $bulan = (int) \Carbon\Carbon::parse($komisi->created_at)->format('n');

            if (!isset($personelData[$nama])) {
                $personelData[$nama] = array_fill(1, 12, 0);
            }

            $personelData[$nama][$bulan] += $komisi->nilai_komisi;
        }

        return view('hod.komisi_total_bulanan', compact('personelData'));
    }
}
