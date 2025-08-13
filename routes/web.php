<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\HodController;
use App\Http\Controllers\PM\PMController;
use App\Http\Controllers\PM\KomisiPMController;
use App\Http\Controllers\PM\ProjectPMController;
use App\Http\Controllers\PM\ProjectDocumentController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\KomisiStaffController;
use App\Http\Controllers\Staff\ProjectStaffController;
use App\Http\Controllers\Staff\DocumentStaffController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KelolaUserController;
use App\Http\Controllers\Admin\KomisiController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Hod\ProjectController as HodProjectController;
use App\Http\Controllers\Hod\KomisiController as HodKomisiController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/



// ============ AUTH ============
// Logout secara resmi → memastikan session & token dihapus
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::delete('/admin/kelola-user/{id}', [KelolaUserController::class, 'destroy'])->name('kelola-user.destroy');
Route::put('/admin/kelola-user/{id}', [KelolaUserController::class, 'update'])->name('kelola-user.update');
Route::get('/admin/kelola-user/search', [KelolaUserController::class, 'search'])->name('kelola-user.search');


// ============ HALAMAN UTAMA (WELCOME) ============
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::resource('kelola-user', App\Http\Controllers\Admin\KelolaUserController::class);
});
Route::resource('kelola-user', KelolaUserController::class);
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/komisi', [KomisiController::class, 'index'])->name('komisi.index');
    Route::get('/project/{id}', [AdminProjectController::class, 'show'])->name('admin.project.show');
});

// user
Route::put('/users/{id}', [UserController::class, 'update']);
//profil
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


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
    Route::get('/hod/dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::get('/pm/dashboard', [PMController::class, 'index'])->name('pm.dashboard');
    Route::post('/pm/projects/{project}/documents', [ProjectDocumentController::class, 'store'])
        ->name('pm.project.documents.store');
    Route::post('/staff/projects/{project}/documents', [DocumentStaffController::class, 'store'])
        ->name('staff.project.documents.store');
    Route::get('/project/{id}', [ProjectStaffController::class, 'show'])->name('staff.project.show');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/ajax-store', [ProjectController::class, 'ajaxStore'])->name('projects.ajax.store');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

Route::get('/staff/komisi', [KomisiStaffController::class, 'index'])->name('staff.komisi');
Route::get('/staff/project', [ProjectStaffController::class, 'index'])->name('staff.project');

Route::get('/pm/komisi', [KomisiPMController::class, 'index'])->name('pm.komisi');
Route::get('/pm/project', [ProjectPMController::class, 'index'])->name('pm.project');


// web.php

// ============ ROUTE LOGIN / REGISTER DLL ============

require __DIR__ . '/auth.php';

Route::get('/cek', function () {
    dd(config('app.debug'));
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->prefix('hod')->group(function () {
    Route::get('/project', [HodProjectController::class, 'index'])->name('hod.project');
    Route::get('/komisi', [HodKomisiController::class, 'index'])->name('hod.komisi');
    Route::get('/project/{id}', [HodProjectController::class, 'show'])->name('hod.project.show');
    Route::post('/komisi/{id}/verifikasi', [HodKomisiController::class, 'verifikasiAjax'])->name('hod.komisi.verifikasi');
    Route::post('/komisi/{id}/batalkan', [HodKomisiController::class, 'batalkanVerifikasiAjax'])->name('hod.komisi.batalkan');
});
// routes/web.php
Route::post('/komisi/store', [KomisiPMController::class, 'store'])->name('komisi.store');
Route::get('/komisi', [KomisiPMController::class, 'index'])->name('pm.komisi');
Route::get('/pm/komisi/{project_id}', [KomisiPMController::class, 'show'])->name('pm.komisi.show');
Route::get('/staff/komisi/{project_id}', [KomisiStaffController::class, 'show'])->name('staff.komisi.show');
Route::get('/admin/komisi/{project_id}', [KomisiController::class, 'show'])->name('admin.komisi.show');
Route::get('/hod/komisi/{project_id}', [HodKomisiController::class, 'show'])->name('hod.komisi.show');
Route::get('/pm/komisi-total', [KomisiPMController::class, 'totalPerPersonel'])->name('pm.komisi.total');
Route::get('/staff/komisi-total', [KomisiStaffController::class, 'totalPerPersonel'])->name('staff.komisi.total');
Route::get('/hod/komisi-total', [HodKomisiController::class, 'totalPerPersonel'])->name('hod.komisi.total');
Route::get('/admin/komisi-total', [KomisiController::class, 'totalPerPersonel'])->name('admin.komisi.total');
Route::get('/pm/komisi-total-bulanan', [KomisiPMController::class, 'totalPerPersonelBulananTable'])->name('pm.komisi.total.bulanan');
Route::get('/staff/komisi-total-bulanan', [KomisiStaffController::class, 'totalPerPersonelBulananTable'])->name('staff.komisi.total.bulanan');
Route::get('/hod/komisi-total-bulanan', [HodKomisiController::class, 'totalPerPersonelBulananTable'])->name('hod.komisi.total.bulanan');
Route::get('/admin/komisi-total-bulanan', [KomisiController::class, 'totalPerPersonelBulananTable'])->name('admin.komisi.total.bulanan');

