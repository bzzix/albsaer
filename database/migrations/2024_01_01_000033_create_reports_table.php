<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول التقارير
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->enum('report_type', ['weekly', 'monthly', 'institutional'])->default('weekly');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->json('data')->nullable()->comment('بيانات التقرير');
            $table->string('pdf_path')->nullable();
            $table->string('excel_path')->nullable();
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->dateTime('generated_at');
            $table->timestamps();
            
            $table->index('report_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
