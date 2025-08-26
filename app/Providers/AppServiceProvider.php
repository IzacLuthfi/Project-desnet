<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ini_set('memory_limit', '1024M'); // kalau mau tetap dipakai taruh di sini
        // DB::statement("SET time_zone = '+07:00'"); // ini butuh import DB, kalau perlu
    }
}
