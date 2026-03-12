<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول المشاريع التدريبية
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->string('name')->comment('اسم المشروع');
            $table->text('description')->nullable()->comment('الوصف');
            $table->date('start_date')->comment('تاريخ البداية');
            $table->date('end_date')->comment('تاريخ النهاية');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('status');
            // $table->rawCheck('end_date > start_date');
        });

        // إضافة FK لـ grade_levels.project_id بعد إنشاء جدول projects
        Schema::table('grade_levels', function (Blueprint $table) {
            $table->foreign('project_id')
                  ->references('id')->on('projects')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // حذف FK من grade_levels أولاً
        Schema::table('grade_levels', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
        });
        
        Schema::dropIfExists('projects');
    }
};
