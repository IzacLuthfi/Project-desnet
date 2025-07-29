<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Dapatkan rute ke mana pengguna harus diarahkan jika tidak terautentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Jika permintaan mengharapkan respon HTML, redirect ke login
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
