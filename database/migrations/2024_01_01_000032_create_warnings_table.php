<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول الإنذارات
     */
    public function up(): void
    {
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->enum('warning_type', ['first', 'second', 'final'])->default('first');
            $table->decimal('absence_percentage', 5, 2)->nullable();
            $table->integer('total_absences')->default(0);
            $table->integer('total_sessions')->default(0);
            $table->date('issued_date');
            $table->string('pdf_path')->nullable();
            $table->boolean('sent_via_whatsapp')->default(false);
            $table->boolean('sent_via_email')->default(false);
            $table->timestamps();
            
            $table->index('warning_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warnings');
    }
};
