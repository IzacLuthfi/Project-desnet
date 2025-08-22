<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }


    /**
     * Tangani proses login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Otentikasi user
        $request->authenticate();

        // Regenerasi session
        $request->session()->regenerate();

        // Ambil user yang login
        $user = Auth::user();

        // Redirect sesuai role
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
                // Jika role tidak dikenal, kembalikan ke login dengan error
                Auth::logout();
                return redirect('/login')->with('error', 'Peran pengguna tidak dikenali.');
        }
    }

    /**
     * Logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();        // Hapus session
        $request->session()->regenerateToken();   // Regenerasi CSRF token

        return redirect('/');
    }
}
