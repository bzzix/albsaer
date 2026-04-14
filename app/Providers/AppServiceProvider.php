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
        \App\Models\Excuse::observe(\App\Observers\ExcuseObserver::class);
        \App\Models\StudySchedule::observe(\App\Observers\StudyScheduleObserver::class);
        \App\Models\DailySession::observe(\App\Observers\DailySessionObserver::class);
        \App\Models\SessionAttendance::observe(\App\Observers\SessionAttendanceObserver::class);
    }
}
