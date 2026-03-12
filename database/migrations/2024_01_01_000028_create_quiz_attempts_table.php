<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول محاولات الطلاب للاختبارات
     */
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->integer('attempt_number');
            $table->dateTime('started_at');
            $table->dateTime('submitted_at')->nullable();
            $table->integer('time_taken_minutes')->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'graded', 'expired'])->default('in_progress');
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('passed')->nullable();
            $table->timestamps();
            
            $table->unique(['quiz_id', 'student_id', 'attempt_number']);
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
