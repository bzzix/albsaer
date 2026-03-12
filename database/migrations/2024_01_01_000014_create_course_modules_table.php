<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول وحدات الدورة
     */
    public function up(): void
    {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title')->comment('عنوان الوحدة');
            $table->text('description')->nullable();
            $table->integer('module_order')->comment('الترتيب');
            $table->integer('duration_hours')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            
            $table->unique(['course_id', 'module_order']);
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_modules');
    }
};
