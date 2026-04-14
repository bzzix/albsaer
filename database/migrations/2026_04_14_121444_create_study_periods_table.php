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
        Schema::create('study_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->json('active_days'); // [0, 1, 2, 3, 4]
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('sessions_count');
            $table->unsignedInteger('session_duration'); // in minutes
            $table->unsignedInteger('break_duration'); // in minutes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_periods');
    }
};
