<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController; // <-- Tambahkan ini

Route::get('/', function () {
    return view('welcome');
});

// Redirect dashboard sesuai role
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

// Route dashboard berdasarkan role
Route::middleware(['auth'])->group(function () {
    // Admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // PM
    Route::get('/pm/dashboard', function () {
        return view('pm.dashboard');
    })->name('pm.dashboard');

    // HOD
    Route::get('/hod/dashboard', function () {
        return view('hod.dashboard');
    })->name('hod.dashboard');

    // Staff
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
});

// Route profil user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== Tambahan: ROUTE PROJECT ====================
Route::middleware(['auth'])->prefix('project')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('project.index');         // Halaman utama
    Route::post('/', [ProjectController::class, 'store'])->name('project.store');        // Simpan data baru
    Route::get('/{id}', [ProjectController::class, 'show'])->name('project.show');       // Detail dokumen
    Route::get('/{id}/edit', [ProjectController::class, 'edit'])->name('project.edit');  // Edit
    Route::put('/{id}', [ProjectController::class, 'update'])->name('project.update');   // Update
    Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('project.destroy'); // Hapus
});

require __DIR__.'/auth.php';
