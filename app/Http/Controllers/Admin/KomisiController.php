<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class KomisiController extends Controller
{
    public function index()
    {
        return view('admin.komisi.index'); // arahkan ke resources/views/admin/komisi/index.blade.php
    }
}
