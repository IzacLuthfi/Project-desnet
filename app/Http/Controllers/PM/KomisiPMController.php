<?php

namespace App\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Komisi;
use App\Models\ProjectCommission;
use Illuminate\Support\Facades\DB;
use App\Models\User; // untuk ambil user HOD
use App\Models\Notification; // untuk simpan notifikasi
use Illuminate\Support\Facades\Auth;
use App\Events\NewNotification; // <=== WAJIB DITAMBAHKAN

class KomisiPMController extends Controller
{
    public function index()
    {
        $projects = Project::with([
            'pm',
            'projectPersonel.user',
            'komisi.projectPersonel.user'
        ])->where('pm_id', Auth::id())
            ->get();
        return view('pm.komisi', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'margin' => 'required|numeric|min:0',
            'komisi' => 'required|array',
            'komisi.*' => 'required|numeric|min:0|max:100',
            'komisi_pm' => 'required|array',
            'komisi_pm.*' => 'required|numeric|min:0|max:100'
        ]);

        $project = Project::findOrFail($request->project_id);

        // === Simpan komisi untuk PM ===
        foreach ($request->komisi_pm as $pmId => $persentasePm) {
            $nilaiKomisiPm = ($request->margin * $persentasePm) / 100;

            ProjectCommission::create([
                'project_id'          => $request->project_id,
                'project_personel_id' => null, // PM tidak pakai tabel project_personel
                'user_id'             => $pmId,
                'margin'              => $request->margin,
                'persentase'          => $persentasePm,
                'nilai_komisi'        => $nilaiKomisiPm,
            ]);
        }

        // === Simpan komisi untuk Personel ===
        foreach ($request->komisi as $personelId => $persentase) {
            $projectPersonel = \App\Models\ProjectPersonel::findOrFail($personelId);
            $userId = $projectPersonel->user_id;
            $nilaiKomisi = ($request->margin * $persentase) / 100;

            ProjectCommission::create([
                'project_id'          => $request->project_id,
                'project_personel_id' => $personelId,
                'user_id'             => $userId,
                'margin'              => $request->margin,
                'persentase'          => $persentase,
                'nilai_komisi'        => $nilaiKomisi,
            ]);
        }

        // === Notifikasi ke HOD ===
        $namaPM = Auth::user()->name;
        $namaWO = $project->judul ?? 'Proyek';

        $hodUsers = User::where('role', 'hod')->get();

        foreach ($hodUsers as $hod) {
            $notif = Notification::create([
                'user_id' => $hod->id,
                'message' => "$namaPM telah menambahkan komisi untuk WO proyek $namaWO, harap diverifikasi",
                'is_read' => false,
            ]);

            event(new NewNotification($hod->id, $notif->message, 'hod-notifications'));
        }

        return redirect()->back()->with('success', 'Komisi berhasil disimpan dan notifikasi telah dikirim ke HOD.');
    }

    public function destroy($id)
    {
        $komisi = Komisi::findOrFail($id);
        $komisi->delete();

        return redirect()->back()->with('success', 'Komisi berhasil dihapus.');
    }

    public function update(Request $request, $projectId)
    {
        DB::transaction(function () use ($request, $projectId) {
            // PM
            $pmArray = $request->input('komisi_pm', []);                 // [user_id => persen]
            $pmId    = array_key_first($pmArray);
            $pmPct   = $pmArray[$pmId] ?? 0;
            $pmRowId = $request->input('komisi_pm_id');                  // id lama (boleh null)

            ProjectCommission::updateOrCreate(
                $pmRowId ? ['id' => $pmRowId] : ['project_id' => $projectId, 'user_id' => $pmId],
                [
                    'project_id'  => $projectId,
                    'user_id'     => $pmId,
                    'persentase'  => $pmPct,
                    'margin'      => $request->margin ?? 0,
                    'nilai_komisi' => ($request->margin ?? 0) * ($pmPct / 100),
                ]
            );

            // Personel
            $persen = $request->input('komisi', []);                     // [project_personel_id => persen]
            $ids    = $request->input('komisi_id', []);                  // [project_personel_id => commission_id]

            foreach ($persen as $ppId => $pct) {
                $rowId = $ids[$ppId] ?? null;

                ProjectCommission::updateOrCreate(
                    $rowId ? ['id' => $rowId] : ['project_id' => $projectId, 'project_personel_id' => $ppId],
                    [
                        'project_id'          => $projectId,
                        'project_personel_id' => $ppId,
                        'persentase'          => $pct,
                        'margin'              => $request->margin ?? 0,
                        'nilai_komisi'        => ($request->margin ?? 0) * ($pct / 100),
                    ]
                );
            }
        });

        return back()->with('success', 'Komisi berhasil diperbarui.');
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
        // Gunakan model ProjectCommission agar data PM juga ikut
        $komisiSemuaProject = ProjectCommission::with(['projectPersonel.user', 'user'])->get();

        return view('pm.komisi_total', compact('komisiSemuaProject'));
    }

    public function totalPerPersonelBulananTable()
    {
        $komisiSemuaProject = ProjectCommission::with(['projectPersonel.user', 'user'])->get();
        //                              

        $personelData = [];

        foreach ($komisiSemuaProject as $komisi) {
            // ambil nama, fallback kalau null
            $nama = $komisi->user->name ?? ($komisi->projectPersonel->user->name ?? '-');

            $bulan = (int) \Carbon\Carbon::parse($komisi->created_at)->format('n'); // 1-12

            if (!isset($personelData[$nama])) {
                $personelData[$nama] = array_fill(1, 12, 0);
            }

            $personelData[$nama][$bulan] += $komisi->nilai_komisi;
        }

        return view('pm.komisi_total_bulanan', compact('personelData'));
    }
}
