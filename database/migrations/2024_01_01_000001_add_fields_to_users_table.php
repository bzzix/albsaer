<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تحديث جدول المستخدمين - إضافة حقول إضافية
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email')->comment('رقم الهاتف');
            $table->string('avatar')->nullable()->after('phone')->comment('الصورة الشخصية');
            $table->boolean('is_active')->default(true)->after('avatar')->comment('نشط');
            $table->string('guardian_phone', 20)->nullable()->after('is_active')->comment('رقم ولي الأمر');
            
            $table->index('phone');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['phone']);
            $table->dropIndex(['is_active']);
            $table->dropColumn(['phone', 'avatar', 'is_active', 'guardian_phone']);
        });
    }
};
