<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول درجات الاختبارات
     */
    public function up(): void
    {
        Schema::create('quiz_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('best_attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->decimal('final_score', 8, 2);
            $table->decimal('final_percentage', 5, 2);
            $table->boolean('passed');
            $table->foreignId('graded_by')->constrained('users')->onDelete('cascade');
            $table->dateTime('graded_at');
            $table->text('feedback')->nullable();
            $table->timestamps();
            
            $table->unique(['quiz_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_grades');
    }
};
