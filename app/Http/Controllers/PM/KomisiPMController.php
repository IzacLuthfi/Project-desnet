<?php

namespace App\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Komisi;
use App\Models\User; // untuk ambil user HOD
use App\Models\Notification; // untuk simpan notifikasi
use Illuminate\Support\Facades\Auth;
use App\Events\NewNotification; // <=== WAJIB DITAMBAHKAN

class KomisiPMController extends Controller
{
    public function index()
    {
        $projects = Project::with([
            'projectPersonel.user',
            'komisi.projectPersonel.user'
        ])  ->where('pm_id', Auth::id())
            ->get();
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
        $projectPersonel = \App\Models\ProjectPersonel::findOrFail($personelId);
        $userId = $projectPersonel->user_id;
        $nilaiKomisi = ($request->margin * $persentase) / 100;

        Komisi::create([
            'project_id'          => $request->project_id,
            'project_personel_id' => $personelId,
            'user_id'             => $userId,
            'margin'              => $request->margin,
            'persentase'          => $persentase,
            'nilai_komisi'        => $nilaiKomisi,
        ]);
    }

    // === Tambahkan Notifikasi untuk HOD ===
    $project = Project::findOrFail($request->project_id);
    $namaPM = Auth::user()->name;
    $namaWO = $project->judul ?? 'Proyek';

    // Ambil semua HOD
    $hodUsers = User::where('role', 'hod')->get();

    foreach ($hodUsers as $hod) {
        $notif = Notification::create([
            'user_id' => $hod->id,
            'message' => "$namaPM telah menambahkan komisi untuk WO proyek $namaWO, harap diverifikasi",
            'is_read' => false,
        ]);

        // Kirim event pusher realtime
        event(new NewNotification($hod->id, $notif->message, 'hod-notifications'));
    }

    return redirect()->back()->with('success', 'Komisi berhasil disimpan dan notifikasi telah dikirim ke HOD.');
}
    
    public function show($project_id)
    {
        $project = Project::with([
            'komisi.projectPersonel.user'
        ])->findOrFail($project_id);

        return view('pm.komisi_detail', compact('project'));
    }

    public function totalPerPersonel()
    {
        // Ambil semua data komisi + relasi personel & user
        $komisiSemuaProject = Komisi::with('projectPersonel.user')->get();

        // Kirim ke view
        return view('pm.komisi_total', compact('komisiSemuaProject'));
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

        return view('pm.komisi_total_bulanan', compact('personelData'));
    }
 
}