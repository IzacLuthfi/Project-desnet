<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminProjectController extends Controller
{
    public function show($id)
    {
        $project = Project::with('projectDocuments')->findOrFail($id);
        return view('admin.project-detail', compact('project'));
    }
    public function destroyDocument($id)
    {
        $document = \App\Models\ProjectDocument::findOrFail($id);

        // cek kalau file ada di public/documents/
        $filePath = public_path($document->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $document->delete();

        return response()->json(['success' => true]);
    }
}
