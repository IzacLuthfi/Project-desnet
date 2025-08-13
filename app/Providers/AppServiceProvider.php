<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot()
{
    ini_set('memory_limit', '1024M'); // Tambahkan baris ini
    DB::statement("SET time_zone = '+07:00'");
}
}
