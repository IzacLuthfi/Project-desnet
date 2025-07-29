<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Tangani request yang masuk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect sesuai role jika user sudah login
                $user = Auth::user();
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
                        return redirect('/'); // fallback
                }
            }
        }

        return $next($request);
    }
}
