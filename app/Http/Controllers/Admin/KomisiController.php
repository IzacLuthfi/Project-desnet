<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.komisi.index', compact('projects'));
    }
    public function show($project_id)
    {
        $project = Project::with([
            'komisi.projectPersonel.user',
            'komisi.user'
        ])->findOrFail($project_id);

        return view('admin.komisi_detail', compact('project'));
    }
    public function totalPerPersonel()
    {
        // Ambil semua data komisi + relasi personel & user
        $komisiSemuaProject = ProjectCommission::with('projectPersonel.user', 'user')->get();

        // Kirim ke view
        return view('admin.komisi_total', compact('komisiSemuaProject'));
    }

    public function totalPerPersonelBulananTable()
    {
        $komisiSemuaProject = ProjectCommission::with('projectPersonel.user', 'user')->get();

        // Siapkan struktur data
        $personelData = [];

        foreach ($komisiSemuaProject as $komisi) {
            $nama = $komisi->user?->name ?? $komisi->projectPersonel?->user?->name ?? '-';
            $bulan = (int) \Carbon\Carbon::parse($komisi->created_at)->format('n'); // 1-12

            if (!isset($personelData[$nama])) {
                $personelData[$nama] = array_fill(1, 12, 0); // Januari-Desember
            }

            $personelData[$nama][$bulan] += $komisi->nilai_komisi;
        }

        return view('admin.komisi_total_bulanan', compact('personelData'));
    }
}
