<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول أسئلة الاختبارات
     */
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'essay', 'fill_blank', 'matching']);
            $table->decimal('marks', 5, 2);
            $table->integer('question_order');
            $table->text('explanation')->nullable()->comment('شرح بعد الإجابة');
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            
            $table->index(['quiz_id', 'question_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
