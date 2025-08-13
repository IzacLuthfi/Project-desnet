<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Komisi;
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
    public function totalPerPersonel()
    {
        // Ambil semua data komisi + relasi personel & user
        $komisiSemuaProject = Komisi::with('projectPersonel.user')->get();

        // Kirim ke view
        return view('hod.komisi_total', compact('komisiSemuaProject'));
    }

    public function totalPerPersonelBulananTable()
    {
        $komisiSemuaProject = \App\Models\Komisi::with('projectPersonel.user')->get();

        // Siapkan struktur data
        $personelData = [];

        foreach ($komisiSemuaProject as $komisi) {
            $nama = $komisi->projectPersonel->user->name ?? '-';
            $bulan = (int) \Carbon\Carbon::parse($komisi->created_at)->format('n'); // 1-12

            if (!isset($personelData[$nama])) {
                $personelData[$nama] = array_fill(1, 12, 0); // Januari-Desember
            }

            $personelData[$nama][$bulan] += $komisi->nilai_komisi;
        }

        return view('hod.komisi_total_bulanan', compact('personelData'));
    }
}
