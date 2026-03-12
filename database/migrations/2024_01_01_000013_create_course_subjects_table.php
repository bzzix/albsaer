<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول ربط الدورات بالمواد
     */
    public function up(): void
    {
        Schema::create('course_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->integer('weekly_hours')->default(0);
            $table->integer('total_hours')->default(0);
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['course_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_subjects');
    }
};
