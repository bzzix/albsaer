<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول مستويات الطلاب - تتبع التقدم
     */
    public function up(): void
    {
        Schema::create('student_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->date('enrollment_date')->comment('تاريخ الالتحاق');
            $table->date('completion_date')->nullable()->comment('تاريخ الإكمال');
            $table->enum('status', ['active', 'completed', 'failed', 'transferred'])->default('active');
            $table->boolean('is_current')->default(true)->comment('المستوى الحالي');
            $table->timestamps();
            
            $table->unique(['student_id', 'grade_level_id', 'academic_year_id']);
            $table->index(['student_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_levels');
    }
};
