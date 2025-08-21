<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $projects = Project::with('projectPersonel')->get();
        $projectManagers = User::where('role', 'pm')->get();
        $staffs = User::where('role', 'staff')->get();

        // Hitung statistik dokumen berdasarkan project milik PM tersebut
        $totalDokumen = $projects->count();
        $dokumenRevisi = $projects->where('status_dokumen', 'Revisi')->count();
        $dokumenSelesai = $projects->where('status_dokumen', 'Sudah Diajukan')->count();

        $stats = [
            'total' => $totalDokumen,
            'revisi' => $dokumenRevisi,
            'selesai' => $dokumenSelesai,
        ];

        // Hitung komisi bulan ini (tanpa filter status_komisi)
        $totalKomisiBulanIni = DB::table('project_commissions')
            ->join('projects', 'project_commissions.project_id', '=', 'projects.id')
            ->whereMonth('project_commissions.created_at', now()->month)
            ->whereYear('project_commissions.created_at', now()->year)
            ->sum('project_commissions.nilai_komisi');

        // Hitung total semua komisi
        $totalKomisiKeseluruhan = DB::table('project_commissions')
            ->join('projects', 'project_commissions.project_id', '=', 'projects.id')
            ->sum('project_commissions.nilai_komisi');

        $komisi = [
            'bulan' => $totalKomisiBulanIni,
            'total' => $totalKomisiKeseluruhan,
        ];

        return view('admin.dashboard', compact('projects', 'stats', 'komisi', 'projectManagers', 'staffs'));
    }
}
