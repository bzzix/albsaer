<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'slug' => 'welcome_user',
                'name' => 'رسالة الترحيب بالمستخدم الجديد',
                'email_subject' => 'مرحباً بك في {{site_name}}',
                'email_content' => '<p>مرحباً {{user_name}}،</p><p>سعداء جداً بانضمامك إلينا في <strong>{{site_name}}</strong>.</p><p>يمكنك الآن البدء في استخدام كافة مميزات النظام ومتابعة أعمالك بكل سهولة.</p><p>رابط الدخول: {{login_url}}<br>بريدك الإلكتروني: {{email}}</p><p>نتمنى لك تجربة مثمرة!</p>',
                'whatsapp_content' => "مرحباً {{user_name}} 👋\n\nسعداء بانضمامك إلى *{{site_name}}*.\nنتطلع لمساعدتك في تحقيق أهدافك!\n\nرابط الدخول: {{login_url}}\nحسابك: {{email}}",
                'variables' => ['user_name', 'site_name', 'login_url', 'email', 'date'],
                'is_active' => true,
                'is_email_active' => true,
                'is_whatsapp_active' => true,
                'is_system_active' => true,
            ],
            [
                'slug' => 'permission_changed',
                'name' => 'تنبيه تغيير صلاحيات الوصول',
                'email_subject' => 'تحديث في صلاحيات حسابك - {{site_name}}',
                'email_content' => '<p>عزيزي {{user_name}}،</p><p>نود إبلاغك بأنه تم تحديث صلاحيات الوصول الخاصة بحسابك بواسطة الإدارة.</p><p><strong>الصلاحية السابقة:</strong> {{old_role}}<br><strong>الصلاحية الجديدة:</strong> {{new_role}}</p><p>بواسطة: {{admin_name}}</p><p>إذا كان لديك أي استفسار، يرجى التواصل مع الدعم الفني.</p>',
                'whatsapp_content' => "تنبيه هام 🔐\n\nعزيزي {{user_name}}، تم تحديث صلاحيات حسابك في *{{site_name}}*.\n\nمن: {{old_role}}\nإلى: {{new_role}}\nبواسطة: {{admin_name}}\n\nشكراً لك.",
                'variables' => ['user_name', 'site_name', 'old_role', 'new_role', 'admin_name', 'date'],
                'is_active' => true,
                'is_email_active' => true,
                'is_whatsapp_active' => true,
                'is_system_active' => true,
            ],
            [
                'slug' => 'settings_updated',
                'name' => 'إشعار تحديث إعدادات النظام',
                'email_subject' => 'تنبيه: تم تحديث إعدادات هامة في النظام',
                'email_content' => '<p>مرحباً،</p><p>تم رصد تحديث في إعدادات النظام الحساسة:</p><p><strong>الإعداد:</strong> {{setting_label}}<br><strong>القيمة القديمة:</strong> {{old_value}}<br><strong>القيمة الجديدة:</strong> {{new_value}}</p><p>تم التعديل بواسطة: {{admin_name}}</p><p>التاريخ: {{date}}</p>',
                'whatsapp_content' => "تنبيه إداري ⚙️\n\nتم تحديث إعدادات في النظام:\n*{{setting_label}}*\n\nالقيمة الجديدة: {{new_value}}\nبواسطة: {{admin_name}}\nالتاريخ: {{date}}",
                'variables' => ['setting_label', 'old_value', 'new_value', 'admin_name', 'date'],
                'is_active' => true,
                'is_email_active' => true,
                'is_whatsapp_active' => true,
                'is_system_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
