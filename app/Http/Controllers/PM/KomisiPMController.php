<?php

namespace App\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class KomisiPMController extends Controller
{
    public function index()
    {
        // Ambil semua data project (bisa disesuaikan dengan user login jika perlu)
        $projects = Project::all();

        // Hitung statistik dokumen
        $totalDokumen = $projects->count();
        $dokumenRevisi = $projects->where('status_dokumen', 'Revisi')->count();
        $dokumenSelesai = $projects->where('status_dokumen', 'Sudah Diajukan')->count();

        $stats = [
            'total' => $totalDokumen,
            'revisi' => $dokumenRevisi,
            'selesai' => $dokumenSelesai,
        ];

        // Komisi dummy (kamu bisa ganti sesuai logika komisi aslinya)
        $komisi = [
            'bulan' => 76000000,
            'tahun' => 1546000000,
        ];

        return view('pm.komisi', compact('projects', 'stats', 'komisi'));
    }
}
