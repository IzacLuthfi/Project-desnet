<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\HodController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/



// ============ AUTH ============
// Logout secara resmi → memastikan session & token dihapus
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ============ HALAMAN UTAMA (WELCOME) ============
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ============ REDIRECT DASHBOARD PER ROLE ============
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

Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pm/dashboard', fn() => view('pm.dashboard'))->name('pm.dashboard');
    Route::get('/hod/dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    Route::get('/staff/dashboard', fn() => view('staff.dashboard'))->name('staff.dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/ajax-store', [ProjectController::class, 'ajaxStore'])->name('projects.ajax.store');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

// ============ ROUTE LOGIN / REGISTER DLL ============
require __DIR__ . '/auth.php';
