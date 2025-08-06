<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
class KomisiController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectPersonel')->get();
        return view('admin.komisi.index' , compact('projects')); // arahkan ke resources/views/admin/komisi/index.blade.php

    }
}
