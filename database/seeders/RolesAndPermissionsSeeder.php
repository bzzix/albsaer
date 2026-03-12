<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مسح كاش الصلاحيات
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. إنشاء الصلاحيات الأساسية (Permissions) مع تحديد اللون
        $permissions = [
            // لوحة التحكم
            ['name' => 'view_dashboard', 'display_name' => 'عرض لوحة التحكم', 'display_name_en' => 'View Dashboard', 'color' => '#8b5cf6'],
            
            // المستخدمين والصلاحيات
            ['name' => 'view_users', 'display_name' => 'عرض المستخدمين', 'display_name_en' => 'View Users', 'color' => '#ef4444'],
            ['name' => 'manage_users', 'display_name' => 'إدارة المستخدمين', 'display_name_en' => 'Manage Users', 'color' => '#ef4444'],
            ['name' => 'manage_roles', 'display_name' => 'إدارة الصلاحيات', 'display_name_en' => 'Manage Roles', 'color' => '#ef4444'],
            
            // الإعدادات
            ['name' => 'manage_settings', 'display_name' => 'إدارة الإعدادات', 'display_name_en' => 'Manage Settings', 'color' => '#64748b'],
            
            // الدورات
            ['name' => 'view_courses', 'display_name' => 'عرض الدورات', 'display_name_en' => 'View Courses', 'color' => '#3b82f6'],
            ['name' => 'manage_courses', 'display_name' => 'إدارة الدورات', 'display_name_en' => 'Manage Courses', 'color' => '#3b82f6'],
            
            // المجموعات
            ['name' => 'view_groups', 'display_name' => 'عرض المجموعات', 'display_name_en' => 'View Groups', 'color' => '#0ea5e9'],
            ['name' => 'manage_groups', 'display_name' => 'إدارة المجموعات', 'display_name_en' => 'Manage Groups', 'color' => '#0ea5e9'],
            
            // الحضور
            ['name' => 'view_attendance', 'display_name' => 'عرض الحضور', 'display_name_en' => 'View Attendance', 'color' => '#22c55e'],
            ['name' => 'manage_attendance', 'display_name' => 'إدارة الحضور', 'display_name_en' => 'Manage Attendance', 'color' => '#22c55e'],
            
            // الدرجات
            ['name' => 'view_grades', 'display_name' => 'عرض الدرجات', 'display_name_en' => 'View Grades', 'color' => '#10b981'],
            ['name' => 'manage_grades', 'display_name' => 'إدارة الدرجات', 'display_name_en' => 'Manage Grades', 'color' => '#10b981'],
            
            // التقارير
            ['name' => 'view_reports', 'display_name' => 'عرض التقارير', 'display_name_en' => 'View Reports', 'color' => '#f59e0b'],
            
            // المقالات
            ['name' => 'manage_articles', 'display_name' => 'إدارة المقالات', 'display_name_en' => 'Manage Articles', 'color' => '#db2777'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                [
                    'display_name' => $perm['display_name'], 
                    'display_name_en' => $perm['display_name_en'],
                    'color' => $perm['color']
                ]
            );
        }

        // 2. إنشاء الأدوار وتخصيص الصلاحيات مع تحديد الألوان
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'مدير عام',
                'display_name_en' => 'Super Admin',
                'color' => '#dc2626',
                'permissions' => 'all'
            ],
            [
                'name' => 'admin',
                'display_name' => 'مدير النظام',
                'display_name_en' => 'Admin',
                'color' => '#4f46e5',
                'permissions' => 'all'
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'مشرف',
                'display_name_en' => 'Supervisor',
                'color' => '#d97706',
                'permissions' => [
                    'view_dashboard', 'view_users', 'view_courses', 'view_groups', 
                    'manage_groups', 'view_attendance', 'manage_attendance', 'view_grades', 'view_reports'
                ]
            ],
            [
                'name' => 'instructor',
                'display_name' => 'مدرب',
                'display_name_en' => 'Instructor',
                'color' => '#0891b2',
                'permissions' => [
                    'view_dashboard', 'view_groups', 'view_attendance', 
                    'manage_attendance', 'view_grades', 'manage_grades'
                ]
            ],
            [
                'name' => 'student',
                'display_name' => 'طالب',
                'display_name_en' => 'Student',
                'color' => '#16a34a',
                'permissions' => [
                    'view_dashboard', 'view_attendance', 'view_grades'
                ]
            ],
            [
                'name' => 'parent',
                'display_name' => 'ولي الأمر',
                'display_name_en' => 'Parent',
                'color' => '#9333ea',
                'permissions' => [
                    'view_dashboard', 'view_attendance', 'view_grades', 'view_reports'
                ]
            ],
            [
                'name' => 'author',
                'display_name' => 'مؤلف',
                'display_name_en' => 'Author',
                'color' => '#db2777',
                'permissions' => [
                    'view_dashboard', 'manage_articles'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(
                ['name' => $roleData['name'], 'guard_name' => 'web'],
                [
                    'display_name' => $roleData['display_name'],
                    'display_name_en' => $roleData['display_name_en'],
                    'color' => $roleData['color']
                ]
            );

            if ($roleData['permissions'] === 'all') {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($roleData['permissions']);
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->command->info('تم إنشاء ' . count($roles) . ' أدوار وتخصيص الصلاحيات والألوان بنجاح!');
    }
}
