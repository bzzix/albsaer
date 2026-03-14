<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed settings first
        $this->call([
            SettingsSeeder::class,
            RolesAndPermissionsSeeder::class,
            SettingsSeeder::class,
            NotificationTemplateSeeder::class,
        ]);

        // 1. Super Admin
        $superAdmin = User::firstOrCreate([
            'email' => 'super@albsaer.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('super_admin');

        // 2. Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@albsaer.com',
        ], [
            'name' => 'System Admin',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // 3. Instructor
        $instructor = User::firstOrCreate([
            'email' => 'instructor@albsaer.com',
        ], [
            'name' => 'Test Instructor',
            'password' => bcrypt('password'),
        ]);
        $instructor->assignRole('instructor');

        // 4. Student
        $student = User::firstOrCreate([
            'email' => 'student@albsaer.com',
        ], [
            'name' => 'Test Student',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole('student');

        // 5. Parent
        $parent = User::firstOrCreate([
            'email' => 'parent@albsaer.com',
        ], [
            'name' => 'Test Parent',
            'password' => bcrypt('password'),
        ]);
        $parent->assignRole('parent');
    }
}
