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

        $project->projectDocuments()->create([
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path' => $filePath,
            'nama_asli' => $originalName,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }
}
