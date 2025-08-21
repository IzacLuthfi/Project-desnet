<?php

namespace App\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectCommission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PMController extends Controller
{
    public function index()
    {
        // Ambil project hanya milik PM yang sedang login
        $projects = Project::where('pm_id', Auth::id())->get();

        // Hitung statistik dokumen berdasarkan project milik PM tersebut
        $totalDokumen = $projects->count();
        $dokumenSelesai = $projects->where('status_dokumen', 'Sudah Diajukan')->count();

        $stats = [
            'total' => $totalDokumen,
            'selesai' => $dokumenSelesai,
        ];

        // Hitung komisi bulan ini (tanpa filter status_komisi)
        $totalKomisiBulanIni = DB::table('project_commissions')
            ->join('projects', 'project_commissions.project_id', '=', 'projects.id')
            ->where('projects.pm_id', Auth::id())
            ->whereMonth('project_commissions.created_at', now()->month)
            ->whereYear('project_commissions.created_at', now()->year)
            ->sum('project_commissions.nilai_komisi');

        // Hitung total semua komisi
        $totalKomisiKeseluruhan = DB::table('project_commissions')
            ->join('projects', 'project_commissions.project_id', '=', 'projects.id')
            ->where('projects.pm_id', Auth::id())
            ->sum('project_commissions.nilai_komisi');

        $komisi = [
            'bulan' => $totalKomisiBulanIni,
            'total' => $totalKomisiKeseluruhan,
        ];

        return view('pm.dashboard', compact('projects', 'stats', 'komisi'));
    }
}
