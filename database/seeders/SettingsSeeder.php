<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("🔧 Seeding settings...");
        
        $this->seedGeneralSettings();
        $this->seedAcademicSettings();
        $this->seedAttendanceSettings();
        $this->seedWarningSettings();
        $this->seedNotificationSettings();
        $this->seedEmailSettings();
        $this->seedWhatsAppSettings();
        $this->seedReportSettings();
        $this->seedSystemSettings();

        // إعادة بناء الكاش بعد الانتهاء
        $this->command->info("🔄 Rebuilding cache...");
        $stats = rebuild_settings_cache();
        
        $this->command->info("✅ Settings seeded successfully!");
        $this->command->info("📊 Total: {$stats['total_settings']} settings in {$stats['total_groups']} groups");
        $this->command->info("⚡ Cache rebuilt in {$stats['duration_ms']}ms");
    }

    /**
     * إعدادات عامة للموقع
     */
    private function seedGeneralSettings(): void
    {
        $settings = [
            'site_name' => ['value' => 'معهد البصائر', 'type' => 'string', 'description' => 'اسم الموقع', 'public' => true],
            'site_name_en' => ['value' => 'Albsaer Institute', 'type' => 'string', 'description' => 'اسم الموقع بالإنجليزية', 'public' => true],
            'site_email' => ['value' => 'info@albsaer.com', 'type' => 'string', 'description' => 'البريد الإلكتروني للموقع', 'public' => true],
            'site_phone' => ['value' => '', 'type' => 'string', 'description' => 'رقم هاتف الموقع', 'public' => true],
            'site_address' => ['value' => '', 'type' => 'string', 'description' => 'عنوان المعهد', 'public' => true],
            'site_description' => ['value' => 'نظام متقدم لإدارة معهد البصائر والطلاب والمجموعات الدراسية.', 'type' => 'string', 'description' => 'وصف الموقع', 'public' => true],
            'meta_tags' => ['value' => ['keywords' => 'معهد, بصائر, تعليم, إدارة طلاب'], 'type' => 'array', 'description' => 'الكلمات المفتاحية للمحركات البحث', 'public' => true],
            'site_logo' => ['value' => '/images/logo.png', 'type' => 'string', 'description' => 'شعار الموقع', 'public' => true],
            'site_favicon' => ['value' => '/images/favicon.ico', 'type' => 'string', 'description' => 'أيقونة الموقع', 'public' => true],
            'timezone' => ['value' => 'Asia/Riyadh', 'type' => 'string', 'description' => 'المنطقة الزمنية', 'public' => true],
            'locale' => ['value' => 'ar', 'type' => 'string', 'description' => 'اللغة الافتراضية', 'public' => true],
        ];

        $this->createSettings($settings, 'general');
    }

    /**
     * إعدادات أكاديمية
     */
    private function seedAcademicSettings(): void
    {
        $settings = [
            'max_students_per_group' => ['value' => 30, 'type' => 'int', 'description' => 'الحد الأقصى للطلاب في المجموعة'],
            'min_students_per_group' => ['value' => 10, 'type' => 'int', 'description' => 'الحد الأدنى للطلاب في المجموعة'],
            'max_groups_per_instructor' => ['value' => 5, 'type' => 'int', 'description' => 'الحد الأقصى للمجموعات لكل مدرب'],
            'academic_year_start_month' => ['value' => 9, 'type' => 'int', 'description' => 'شهر بداية السنة الأكاديمية (سبتمبر)'],
            'semester_duration_weeks' => ['value' => 16, 'type' => 'int', 'description' => 'مدة الفصل الدراسي بالأسابيع'],
            'passing_grade_percentage' => ['value' => 50, 'type' => 'int', 'description' => 'نسبة النجاح المئوية'],
            'max_quiz_attempts' => ['value' => 3, 'type' => 'int', 'description' => 'الحد الأقصى لمحاولات الاختبار'],
            'quiz_time_limit_minutes' => ['value' => 60, 'type' => 'int', 'description' => 'الوقت الافتراضي للاختبار بالدقائق'],
        ];

        $this->createSettings($settings, 'academic');
    }

    /**
     * إعدادات الحضور
     */
    private function seedAttendanceSettings(): void
    {
        $settings = [
            'min_attendance_percentage' => ['value' => 75, 'type' => 'int', 'description' => 'الحد الأدنى لنسبة الحضور'],
            'late_arrival_minutes' => ['value' => 15, 'type' => 'int', 'description' => 'عدد دقائق التأخير المسموح'],
            'auto_mark_absent_time' => ['value' => '22:00', 'type' => 'string', 'description' => 'وقت تسجيل الغياب التلقائي (10 مساءً)'],
            'excuse_submission_deadline_days' => ['value' => 3, 'type' => 'int', 'description' => 'مهلة تقديم الأعذار بالأيام'],
            'max_excuse_attachments' => ['value' => 5, 'type' => 'int', 'description' => 'الحد الأقصى لمرفقات العذر'],
            'excuse_auto_approve' => ['value' => false, 'type' => 'bool', 'description' => 'الموافقة التلقائية على الأعذار'],
        ];

        $this->createSettings($settings, 'attendance');
    }

    /**
     * إعدادات الإنذارات
     */
    private function seedWarningSettings(): void
    {
        $settings = [
            'warning_threshold_1' => ['value' => 20, 'type' => 'int', 'description' => 'نسبة الغياب للإنذار الأول (20%)'],
            'warning_threshold_2' => ['value' => 30, 'type' => 'int', 'description' => 'نسبة الغياب للإنذار الثاني (30%)'],
            'warning_threshold_3' => ['value' => 40, 'type' => 'int', 'description' => 'نسبة الغياب للإنذار النهائي (40%)'],
            'warning_issue_time' => ['value' => '23:00', 'type' => 'string', 'description' => 'وقت إصدار الإنذارات (11 مساءً)'],
            'warning_send_whatsapp' => ['value' => true, 'type' => 'bool', 'description' => 'إرسال الإنذارات عبر واتساب'],
            'warning_send_email' => ['value' => true, 'type' => 'bool', 'description' => 'إرسال الإنذارات عبر البريد'],
            'warning_notify_guardian' => ['value' => true, 'type' => 'bool', 'description' => 'إشعار ولي الأمر بالإنذار'],
        ];

        $this->createSettings($settings, 'warnings');
    }

    /**
     * إعدادات الإشعارات
     */
    private function seedNotificationSettings(): void
    {
        $settings = [
            'notify_student_enrollment' => ['value' => true, 'type' => 'bool', 'description' => 'إشعار الطالب عند التسجيل'],
            'notify_grade_published' => ['value' => true, 'type' => 'bool', 'description' => 'إشعار نشر الدرجات'],
            'notify_quiz_available' => ['value' => true, 'type' => 'bool', 'description' => 'إشعار توفر اختبار جديد'],
            'notify_absence' => ['value' => true, 'type' => 'bool', 'description' => 'إشعار الغياب اليومي'],
            'notify_guardian_absence' => ['value' => true, 'type' => 'bool', 'description' => 'إشعار ولي الأمر بالغياب'],
            'notification_channels' => ['value' => ['database', 'whatsapp', 'email'], 'type' => 'array', 'description' => 'قنوات الإشعارات'],
        ];

        $this->createSettings($settings, 'notifications');
    }

    /**
     * إعدادات البريد الإلكتروني
     */
    private function seedEmailSettings(): void
    {
        $settings = [
            'email_driver' => ['value' => 'smtp', 'type' => 'string', 'description' => 'مزود البريد'],
            'email_host' => ['value' => 'smtp.gmail.com', 'type' => 'string', 'description' => 'خادم SMTP'],
            'email_port' => ['value' => 587, 'type' => 'int', 'description' => 'منفذ SMTP'],
            'email_encryption' => ['value' => 'tls', 'type' => 'string', 'description' => 'نوع التشفير'],
            'email_from_address' => ['value' => 'noreply@albsaer.com', 'type' => 'string', 'description' => 'البريد المرسل'],
            'email_from_name' => ['value' => 'معهد البصائر', 'type' => 'string', 'description' => 'اسم المرسل'],
        ];

        $this->createSettings($settings, 'email');
    }

    /**
     * إعدادات WhatsApp API
     */
    private function seedWhatsAppSettings(): void
    {
        $settings = [
            'whatsapp_enabled' => ['value' => false, 'type' => 'bool', 'description' => 'تفعيل واتساب'],
            'whatsapp_api_url' => ['value' => 'https://api.respond.io', 'type' => 'string', 'description' => 'رابط API'],
            'whatsapp_api_key' => ['value' => '', 'type' => 'string', 'description' => 'مفتاح API'],
            'whatsapp_channel_id' => ['value' => '', 'type' => 'string', 'description' => 'معرف القناة'],
            'whatsapp_send_welcome' => ['value' => true, 'type' => 'bool', 'description' => 'إرسال رسالة ترحيب'],
            'whatsapp_send_daily_report' => ['value' => false, 'type' => 'bool', 'description' => 'إرسال تقرير يومي'],
        ];

        $this->createSettings($settings, 'whatsapp');
    }

    /**
     * إعدادات التقارير
     */
    private function seedReportSettings(): void
    {
        $settings = [
            'weekly_report_enabled' => ['value' => true, 'type' => 'bool', 'description' => 'تفعيل التقرير الأسبوعي'],
            'weekly_report_day' => ['value' => 'thursday', 'type' => 'string', 'description' => 'يوم التقرير الأسبوعي'],
            'weekly_report_time' => ['value' => '20:00', 'type' => 'string', 'description' => 'وقت التقرير الأسبوعي (8 مساءً)'],
            'monthly_report_enabled' => ['value' => true, 'type' => 'bool', 'description' => 'تفعيل التقرير الشهري'],
            'monthly_report_day' => ['value' => 1, 'type' => 'int', 'description' => 'يوم التقرير الشهري (أول الشهر)'],
            'report_format' => ['value' => 'pdf', 'type' => 'string', 'description' => 'صيغة التقرير الافتراضية'],
            'report_include_charts' => ['value' => true, 'type' => 'bool', 'description' => 'تضمين الرسوم البيانية'],
        ];

        $this->createSettings($settings, 'reports');
    }

    /**
     * إعدادات النظام
     */
    private function seedSystemSettings(): void
    {
        $settings = [
            'maintenance_mode' => ['value' => false, 'type' => 'bool', 'description' => 'وضع الصيانة'],
            'debug_mode' => ['value' => false, 'type' => 'bool', 'description' => 'وضع التصحيح'],
            'cache_enabled' => ['value' => true, 'type' => 'bool', 'description' => 'تفعيل الكاش'],
            'queue_enabled' => ['value' => true, 'type' => 'bool', 'description' => 'تفعيل الطوابير'],
            'session_lifetime' => ['value' => 120, 'type' => 'int', 'description' => 'مدة الجلسة بالدقائق'],
            'pagination_per_page' => ['value' => 15, 'type' => 'int', 'description' => 'عدد العناصر في الصفحة'],
            'max_upload_size_mb' => ['value' => 10, 'type' => 'int', 'description' => 'الحد الأقصى لحجم الرفع (MB)'],
            'allowed_file_types' => ['value' => ['pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'], 'type' => 'array', 'description' => 'أنواع الملفات المسموحة'],
        ];

        $this->createSettings($settings, 'system');
    }

    /**
     * إنشاء الإعدادات باستخدام set_setting helper
     */
    private function createSettings(array $settings, string $group): void
    {
        foreach ($settings as $key => $data) {
            set_setting(
                key: $key,
                value: $data['value'],
                type: $data['type'],
                group: $group,
                description: $data['description']
            );
            
            // تحديث is_public إذا كان موجوداً
            if (isset($data['public']) && $data['public']) {
                \App\Models\Setting::where('key', $key)->update(['is_public' => true]);
            }
        }

        $count = count($settings);
        $this->command->info("✓ Created {$count} settings in '{$group}' group");
    }
}
