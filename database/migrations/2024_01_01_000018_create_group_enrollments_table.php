<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول تسجيل الطلاب في المجموعات
     */
    public function up(): void
    {
        Schema::create('group_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('enrolled_by')->constrained('users')->onDelete('cascade');
            $table->date('enrollment_date');
            $table->enum('status', ['active', 'completed', 'withdrawn', 'transferred'])->default('active');
            $table->timestamps();
            
            $table->unique(['student_id', 'group_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_enrollments');
    }
};
