<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول مرفقات الدروس
     */
    public function up(): void
    {
        Schema::create('lesson_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('module_lessons')->onDelete('cascade');
            $table->string('file_name')->comment('اسم الملف');
            $table->string('file_path', 500)->comment('مسار الملف');
            $table->string('file_type', 50)->comment('نوع الملف');
            $table->bigInteger('file_size')->nullable()->comment('حجم الملف بالبايت');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_attachments');
    }
};
