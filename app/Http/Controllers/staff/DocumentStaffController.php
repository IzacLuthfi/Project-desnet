<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectDocument;
use App\Models\Project;

class DocumentStaffController extends Controller
{
    public function store(Request $request, Project $project)
{
    $request->validate([
        'jenis_dokumen' => 'required|string',
        'dokumen' => 'required|file',
        'keterangan' => 'nullable|string',
    ]);

    $file = $request->file('dokumen');
    $originalName = $file->getClientOriginalName();
    $filePath = $file->storeAs('documents', $originalName, 'public');

    // Simpan dokumen
    $project->projectDocuments()->create([
        'jenis_dokumen' => $request->jenis_dokumen,
        'file_path' => $filePath,
        'nama_asli' => $originalName,
        'keterangan' => $request->keterangan,
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

    // Broadcast supaya bell admin update real-time
    event(new \App\Events\NewDocumentUploaded($message));

    return back()->with('success', 'Dokumen berhasil diunggah dan notifikasi terkirim.');
}

}