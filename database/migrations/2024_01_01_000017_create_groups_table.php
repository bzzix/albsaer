<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول المجموعات
     */
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->string('name')->comment('اسم المجموعة');
            $table->foreignId('trainer_id')->nullable()->constrained('instructors')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_students')->default(30);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
            
            $table->index('status');
            // $table->rawCheck('(project_id IS NOT NULL OR course_id IS NOT NULL)');
            // $table->rawCheck('end_date > start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
