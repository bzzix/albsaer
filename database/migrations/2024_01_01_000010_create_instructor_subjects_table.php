<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول مواد المدربين
     */
    public function up(): void
    {
        Schema::create('instructor_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructors')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('intermediate');
            $table->date('assigned_date')->comment('تاريخ التعيين');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['instructor_id', 'subject_id']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_subjects');
    }
};
