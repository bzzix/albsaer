<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول المستويات الدراسية
     */
    public function up(): void
    {
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('cascade')->comment('السنة الأكاديمية');
            $table->unsignedBigInteger('project_id')->nullable()->comment('المشروع (سيضاف FK لاحقاً)');
            $table->string('name', 100)->comment('الاسم (الصف الأول)');
            $table->string('name_en', 100)->nullable()->comment('الاسم بالإنجليزية');
            $table->integer('level_order')->comment('الترتيب');
            $table->text('description')->nullable()->comment('الوصف');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->timestamps();
            
            // Indexes
            $table->index('is_active');
            $table->index('level_order');
            $table->index('project_id');
            $table->unique(['academic_year_id', 'level_order'], 'unique_year_level');
            $table->unique(['project_id', 'level_order'], 'unique_project_level');
            
            // Constraint
            // $table->rawCheck('(academic_year_id IS NOT NULL OR project_id IS NOT NULL)');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_levels');
    }
};
