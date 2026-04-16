<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_year_instructor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('instructors')->cascadeOnDelete();
            
            // This JSON column will hold the array of subject IDs the instructor is assigned to for this year.
            $table->json('subject_ids')->nullable()->comment('المواد التي سيدرسها هذا العام');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['academic_year_id', 'instructor_id'], 'year_inst_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_year_instructor');
    }
};
