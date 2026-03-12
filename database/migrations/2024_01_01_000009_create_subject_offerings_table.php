<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول عروض المواد - ربط المواد بالفصول والمستويات
     */
    public function up(): void
    {
        Schema::create('subject_offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->integer('weekly_hours')->default(0)->comment('ساعات أسبوعية');
            $table->integer('total_hours')->default(0)->comment('إجمالي الساعات');
            $table->boolean('is_required')->default(true)->comment('إجبارية');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->timestamps();
            
            $table->unique(['subject_id', 'semester_id', 'grade_level_id'], 'unique_subject_semester_level');
            $table->index('is_active');
            $table->index(['semester_id', 'grade_level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_offerings');
    }
};
