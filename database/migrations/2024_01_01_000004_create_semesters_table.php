<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول الفصول الدراسية
     */
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->string('name', 100)->comment('اسم الفصل');
            $table->integer('semester_number')->comment('رقم الفصل');
            $table->date('start_date')->comment('تاريخ البداية');
            $table->date('end_date')->comment('تاريخ النهاية');
            $table->boolean('is_active')->default(false)->comment('الفصل النشط');
            $table->timestamps();
            
            $table->unique(['academic_year_id', 'semester_number']);
            $table->index('is_active');
            // $table->rawCheck('end_date > start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
