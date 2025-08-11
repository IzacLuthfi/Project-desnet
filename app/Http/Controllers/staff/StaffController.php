<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function dashboard()
    {
        // Ambil semua project beserta personelnya
        $projects = Project::with('projectPersonel')
            ->whereHas('projectPersonel', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();
        // Kirim ke view
        return view('staff.dashboard', compact('projects'));
    }
    public function index()
    {
        // Ambil project hanya milik PM yang sedang login
        $projects = Project::with('projectPersonel')
            ->whereHas('projectPersonel', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();
        // Hitung statistik dokumen berdasarkan project milik PM tersebut
        $totalDokumen = $projects->count();
        $dokumenRevisi = $projects->where('status_dokumen', 'Revisi')->count();
        $dokumenSelesai = $projects->where('status_dokumen', 'Sudah Diajukan')->count();

        $stats = [
            'total' => $totalDokumen,
            'revisi' => $dokumenRevisi,
            'selesai' => $dokumenSelesai,
        ];

        // Komisi dummy (ganti sesuai logika aslinya)
        $komisi = [
            'bulan' => 76000000,
            'tahun' => 1546000000,
        ];

        return view('staff.dashboard', compact('projects', 'stats', 'komisi'));
    }
}