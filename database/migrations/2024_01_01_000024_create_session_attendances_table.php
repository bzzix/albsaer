<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول حضور الحصص
     */
    public function up(): void
    {
        Schema::create('session_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('group_enrollment_id')->nullable()->constrained('group_enrollments')->onDelete('cascade');
            $table->foreignId('course_enrollment_id')->nullable()->constrained('course_enrollments')->onDelete('cascade');
            $table->foreignId('daily_session_id')->constrained('daily_sessions')->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'late', 'excused_absent', 'unexcused_absent'])->default('present');
            $table->foreignId('marked_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'attendance_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_attendances');
    }
};
