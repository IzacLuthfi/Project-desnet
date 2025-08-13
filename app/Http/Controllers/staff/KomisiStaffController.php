<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Komisi;
use Illuminate\Support\Facades\Auth;

class KomisiStaffController extends Controller
{
    public function index()
    {
        $projects = Project::with([
            'projectPersonel.user',
            'komisi.projectPersonel.user'
        ])  ->whereHas('projectPersonel', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();
        return view('staff.komisi', compact('projects'));
    }
    
    public function show($project_id)
    {
        $project = Project::with([
            'komisi.projectPersonel.user'
        ])->findOrFail($project_id);

        return view('staff.komisi_detail', compact('project'));
    }

    public function totalPerPersonel()
    {
        // Ambil semua data komisi + relasi personel & user
        $komisiSemuaProject = Komisi::with('projectPersonel.user')->get();

        // Kirim ke view
        return view('staff.komisi_total', compact('komisiSemuaProject'));
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

        return view('staff.komisi_total_bulanan', compact('personelData'));
    }
}
