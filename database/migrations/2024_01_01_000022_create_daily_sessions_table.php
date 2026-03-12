<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول الحصص اليومية
     */
    public function up(): void
    {
        Schema::create('daily_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_day_id')->constrained('schedule_days')->onDelete('cascade');
            $table->integer('session_number')->comment('رقم الحصة 1-10');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->string('session_name', 100)->nullable();
            $table->timestamps();
            
            $table->unique(['schedule_day_id', 'session_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_sessions');
    }
};
