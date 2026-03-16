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
});