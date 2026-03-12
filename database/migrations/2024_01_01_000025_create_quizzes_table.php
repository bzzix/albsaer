<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول الاختبارات والواجبات
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained('module_lessons')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->string('title')->comment('العنوان');
            $table->text('description')->nullable();
            $table->enum('quiz_type', ['quiz', 'assignment', 'exam', 'practice'])->default('quiz');
            $table->decimal('total_marks', 8, 2)->nullable()->comment('محسوبة من الأسئلة');
            $table->decimal('passing_marks', 8, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_until')->nullable();
            $table->integer('max_attempts')->default(1);
            $table->boolean('show_results')->default(true);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('quiz_type');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
