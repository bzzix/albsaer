<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول الدورات
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->foreignId('grade_level_id')->nullable()->constrained('grade_levels')->onDelete('set null');
            $table->string('code', 50)->unique()->comment('كود الدورة');
            $table->string('name')->comment('اسم الدورة');
            $table->text('description')->nullable();
            $table->integer('total_hours')->nullable()->comment('إجمالي الساعات');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
            
            $table->index('status');
            // $table->rawCheck('end_date > start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
