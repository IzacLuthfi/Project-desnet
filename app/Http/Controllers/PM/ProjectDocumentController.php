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

        $project->projectDocuments()->create([
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path'     => $filePath,
            'nama_asli'     => $originalName,
            'keterangan'    => $request->keterangan,
        ]);

        $project->update([
            'status_dokumen' => 'Sudah Diajukan'
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah dan status dokumen diperbarui.');
    }
}
