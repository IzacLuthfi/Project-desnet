<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;


Route::post('/projects/ajax-store', [ProjectController::class, 'ajaxStore']);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama (welcome)
Route::get('/', function () {
    return view('welcome');
});

// Redirect ke dashboard sesuai role
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login');
    }

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'pm':
            return redirect()->route('pm.dashboard');
        case 'hod':
            return redirect()->route('hod.dashboard');
        case 'staff':
            return redirect()->route('staff.dashboard');
        default:
            abort(403, 'Role tidak dikenali.');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== DASHBOARD PER ROLE ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pm/dashboard', fn () => view('pm.dashboard'))->name('pm.dashboard');
    Route::get('/hod/dashboard', fn () => view('hod.dashboard'))->name('hod.dashboard');
    Route::get('/staff/dashboard', fn () => view('staff.dashboard'))->name('staff.dashboard');
});

// ==================== PROFIL USER ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== PROJECT ROUTES ====================
Route::middleware(['auth'])->group(function () {
    // Resource route utama
    Route::resource('projects', ProjectController::class);

    // ➕ Tambahan: route AJAX untuk store data dari dashboard
    Route::post('/projects/ajax-store', [ProjectController::class, 'ajaxStore'])->name('projects.ajax.store');
});

require __DIR__.'/auth.php';
