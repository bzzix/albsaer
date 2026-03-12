<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->comment('معرف فريد للقالب (e.g. welcome_user)');
            $table->string('name')->comment('اسم القالب');
            $table->string('email_subject')->nullable()->comment('عنوان رسالة البريد');
            $table->text('email_content')->nullable()->comment('محتوى رسالة البريد');
            $table->text('whatsapp_content')->nullable()->comment('محتوى رسالة الواتساب');
            $table->json('variables')->nullable()->comment('قائمة المتغيرات المتاحة');
            $table->boolean('is_active')->default(true)->comment('حالة تفعيل القالب ككل');
            $table->boolean('is_email_active')->default(true)->comment('تفعيل إشعارات البريد');
            $table->boolean('is_whatsapp_active')->default(true)->comment('تفعيل إشعارات الواتساب');
            $table->boolean('is_system_active')->default(true)->comment('تفعيل إشعارات النظام');
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
