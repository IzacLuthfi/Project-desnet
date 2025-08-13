<?php

namespace App\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectDocument;
use App\Models\Project;

class ProjectDocumentController extends Controller
{
    public function store(Request $request, $id)
{
    $project = Project::findOrFail($id);

    $request->validate([
        'jenis_dokumen' => 'required|string',
        'dokumen'       => 'required|file',
        'keterangan'    => 'nullable|string',
    ]);

    $file = $request->file('dokumen');
    $originalName = $file->getClientOriginalName();
    $filePath = $file->storeAs('documents', $originalName, 'public');

    // Simpan dokumen
    $project->projectDocuments()->create([
        'jenis_dokumen' => $request->jenis_dokumen,
        'file_path'     => $filePath,
        'nama_asli'     => $originalName,
        'keterangan'    => $request->keterangan,
    ]);

    // Update status proyek
    $project->update([
        'status_dokumen' => 'Sudah Diajukan'
    ]);

    /**
     * === Notifikasi untuk semua admin ===
     */
    $uploaderName = auth()->user()->name;
    $message = "Dokumen '{$request->jenis_dokumen}' untuk WO '{$project->judul}' telah diunggah oleh {$uploaderName}";

    $adminUsers = \App\Models\User::where('role', 'admin')->get();

    foreach ($adminUsers as $admin) {
        \App\Models\Notification::create([
            'user_id' => $admin->id,
            'message' => $message,
            'is_read' => false
        ]);
    }

    // Trigger broadcast ke frontend
    event(new \App\Events\NewDocumentUploaded($message));

    return back()->with('success', 'Dokumen berhasil diunggah dan status dokumen diperbarui.');
}

}