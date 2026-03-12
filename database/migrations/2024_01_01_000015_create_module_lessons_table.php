<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول دروس الوحدة
     */
    public function up(): void
    {
        Schema::create('module_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->string('title')->comment('عنوان الدرس');
            $table->longText('content')->nullable()->comment('المحتوى HTML');
            $table->enum('lesson_type', ['video', 'text', 'pdf', 'interactive'])->default('text');
            $table->integer('lesson_order')->comment('الترتيب');
            $table->integer('duration_minutes')->nullable();
            $table->string('video_url', 500)->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_free')->default(false)->comment('معاينة مجانية');
            $table->timestamps();
            
            $table->unique(['module_id', 'lesson_order']);
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_lessons');
    }
};
