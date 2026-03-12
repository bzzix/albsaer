<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول الإعدادات
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('مفتاح الإعداد');
            $table->text('value')->nullable()->comment('القيمة');
            $table->string('type', 50)->default('string')->comment('نوع البيانات: string, int, bool, json');
            $table->string('group', 100)->nullable()->comment('المجموعة');
            $table->text('description')->nullable()->comment('الوصف');
            $table->boolean('is_public')->default(false)->comment('عام (يمكن للجميع رؤيته)');
            $table->timestamps();
            
            $table->index('group');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
