<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('dashboard');
    })->name('user.dashboard');
    
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Settings
    Route::get('/dashboard/settings/general', \App\Livewire\Settings\GeneralSettings::class)->name('settings.general');
    Route::get('/dashboard/settings/design', \App\Livewire\Settings\DesignSettings::class)->name('settings.design');
    Route::get('/dashboard/settings/notifications', \App\Livewire\Settings\NotificationSettings::class)->name('settings.notifications');
    Route::get('/dashboard/notification-templates', function() {
        return view('dashboard.notification-templates.index');
    })->name('notification-templates.index');
    Route::get('/dashboard/settings/templates', \App\Livewire\Settings\MessageTemplates::class)->name('settings.templates');

    // User Management
    Route::get('/dashboard/users', \App\Livewire\Users\UsersManager::class)->name('users.index');
    Route::get('/dashboard/roles', \App\Livewire\Roles\RolesManager::class)->name('roles.index');

    // Academic Management
    Route::group(['prefix' => 'dashboard/academic', 'as' => 'dashboard.academic.'], function() {
        Route::get('/academic-years', \App\Livewire\Dashboard\Academic\AcademicYears\AcademicYearManager::class)->name('academic-years.index');
        Route::get('/course-categories', \App\Livewire\Dashboard\Academic\CourseCategories\CourseCategoryManager::class)->name('course-categories.index');
        Route::get('/courses', \App\Livewire\Dashboard\Academic\Courses\CoursesManager::class)->name('courses.index');
        Route::get('/courses/{course}/builder', \App\Livewire\Dashboard\Academic\Courses\CourseBuilder::class)->name('courses.builder');
        Route::get('/projects', \App\Livewire\Dashboard\Academic\Projects\ProjectsManager::class)->name('projects.index');
        Route::get('/groups', \App\Livewire\Dashboard\Academic\Groups\GroupsManager::class)->name('groups.index');
        Route::get('/groups/students', function() { return view('dashboard.coming-soon'); })->name('groups.students');
        Route::get('/schedules/periods', \App\Livewire\Dashboard\Academic\Schedules\StudyPeriodsManager::class)->name('schedules.periods');
        Route::get('/schedules/builder', \App\Livewire\Dashboard\Academic\Schedules\ScheduleBuilder::class)->name('schedules.builder');
        
        Route::get('/instructors', function() { return view('dashboard.coming-soon'); })->name('instructors.index');
        Route::get('/students', function() { return view('dashboard.coming-soon'); })->name('students.index');
    });

    // Other Management
    Route::prefix('dashboard')->group(function() {
        Route::get('/attendance', \App\Livewire\Dashboard\Attendance\DailyAttendanceManager::class)->name('attendance.index');
        Route::get('/exams', function() { return view('dashboard.coming-soon'); })->name('exams.index');
        Route::get('/reports', function() { return view('dashboard.coming-soon'); })->name('reports.index');
        Route::get('/financials', function() { return view('dashboard.coming-soon'); })->name('financials.index');
        Route::get('/resources', function() { return view('dashboard.coming-soon'); })->name('resources.index');
    });

    // Dark Mode Toggle Support
    Route::post('/settings/toggle-dark-mode', function() {
        $current = (bool) get_setting('dark_mode_enabled', false);
        set_setting('dark_mode_enabled', !$current, 'bool');
        return response()->json(['success' => true]);
    })->name('settings.toggle-dark-mode');
});