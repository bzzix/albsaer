<?php

namespace App\Providers;

use App\Models\Excuse;
use App\Models\Student;
use App\Observers\ExcuseObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تسجيل المراقبين (Observers)
        Excuse::observe(ExcuseObserver::class);
    }
}
