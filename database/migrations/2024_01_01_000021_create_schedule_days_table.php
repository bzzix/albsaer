<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول أيام الجدول الدراسي
     */
    public function up(): void
    {
        Schema::create('schedule_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_schedule_id')->constrained('study_schedules')->onDelete('cascade');
            $table->enum('day_of_week', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
            $table->boolean('is_study_day')->default(true);
            $table->integer('sessions_count')->default(0)->comment('عدد الحصص 0-10');
            $table->time('day_start_time')->nullable();
            $table->time('day_end_time')->nullable();
            $table->timestamps();
            
            $table->unique(['study_schedule_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_days');
    }
};
