<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول الجداول الدراسية
     */
    public function up(): void
    {
        Schema::create('study_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('اسم الجدول (صباحي، مسائي)');
            $table->text('description')->nullable();
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('is_active');
            // $table->rawCheck('(group_id IS NOT NULL OR project_id IS NOT NULL OR semester_id IS NOT NULL)');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_schedules');
    }
};
