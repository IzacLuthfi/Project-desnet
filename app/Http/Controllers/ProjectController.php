<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectPersonel;
use App\Models\User;
use App\Models\Notification;
use App\Events\NewPMNotification;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')->get();
        return view('project.index', compact('projects'));
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        // Return response JSON untuk AJAX
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        // Fallback jika bukan AJAX
        return redirect()->route('dashboard')->with('success', 'Data berhasil dihapus.');
    }

    public function ajaxStore(Request $request)
    {
        $project = Project::create([
            'judul' => $request->judul,
            'nilai' => $request->nilai,
            'pm_id' => $request->pm_id,
            'status' => 'Belum dimulai',
            'status_dokumen' => 'Belum Diajukan',
            'status_komisi' => 'Belum Disetujui',
        ]);

        foreach ($request->personel as $p) {
            $project->projectPersonel()->create([
                'user_id' => $p['user_id'],
                'role' => $p['role'],
            ]);
        }

        // 🔹 Buat notifikasi untuk PM
        $notif = Notification::create([
            'user_id' => $request->pm_id,
            'message' => 'Work Order baru ditugaskan: ' . $project->judul,
            'is_read' => false,
        ]);

        // 🔹 Trigger event jika pakai real-time
        event(new NewPMNotification($request->pm_id, $notif->message));

        $project->load('projectPersonel');

        return response()->json([
            'success' => true,
            'message' => 'Proyek berhasil disimpan.',
            'project' => $project,
        ]);
    }



    public function dashboard()
    {
        $projects = Project::with('projectPersonel')->get();
        $projectManagers = User::where('role', 'pm')->get();
        $staffs = User::where('role', 'staff')->get();

        return view('admin.dashboard', compact('projects', 'projectManagers', 'staffs'));
    }

    public function create()
    {
        $projectManagers = User::where('role', 'pm')->get();
        $staffs = User::where('role', 'staff')->get();

        return view('project.create', compact('projectManagers', 'staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'pm_id' => 'required|exists:users,id',
            'personel.*.user_id' => 'required|exists:users,id',
            'personel.*.role' => 'required|string',
        ]);

        $project = Project::create([
            'judul' => $request->judul,
            'nilai' => $request->nilai,
            'pm_id' => $request->pm_id,
            'status' => $request->status ?? 'Belum dimulai',
            'status_dokumen' => 'Belum Diajukan',
            'status_komisi' => 'Belum Disetujui',
        ]);

        if ($request->has('personel')) {
            foreach ($request->personel as $person) {
                ProjectPersonel::create([
                    'project_id' => $project->id,
                    'user_id' => $person['user_id'],
                    'role' => $person['role'],
                ]);
            }
        }
        $notif = Notification::create([
            'user_id' => $request->pm_id,
            'message' => 'Work Order baru ditugaskan: ' . $project->judul,
            'is_read' => false,
        ]);

        event(new NewPMNotification($request->pm_id, $notif->message));

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil disimpan.');
    }

    public function show($id)
    {
        $project = Project::with('projectDocuments')->findOrFail($id);
        return view('pm.project.show', compact('project'));
    }

    public function edit($id)
    {
    $project = Project::with('projectPersonel')->findOrFail($id);

    // ambil semua project kecuali yang sedang di-edit
    $projects = Project::with('projectPersonel')
        ->where('id', '!=', $id)
        ->get();

    // Ambil semua user dengan role Project Manager
    $projectManagers = User::where('role', 'pm')->get();

    // Ambil semua staff untuk pilihan personel
    $staffs = User::where('role', 'Staff')->get();

    return view(
    'admin.projects.edit',
    compact('project', 'projects', 'projectManagers', 'staffs')
);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'pm_id' => 'required|exists:users,id',
            
            'personel.*.user_id' => 'nullable|exists:users,id',
            'personel.*.role' => 'nullable|string',
        ]);

        $project = Project::findOrFail($id);

        $project->update([
            'judul' => $request->judul,
            'nilai' => $request->nilai,
            'pm_id' => $request->pm_id,

        ]);

        // Hapus personel lama
        ProjectPersonel::where('project_id', $project->id)->delete();

        if ($request->has('personel')) {
            foreach ($request->personel as $person) {
                if (!empty($person['user_id'])) {
                    ProjectPersonel::create([
                        'project_id' => $project->id,
                        'user_id' => $person['user_id'],
                        'role' => $person['role'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.dashboard')->with('success', 'Proyek berhasil diperbarui.');
    }
}
