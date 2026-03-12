<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول مدربي الحصص
     */
    public function up(): void
    {
        Schema::create('session_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_session_id')->constrained('daily_sessions')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('instructors')->onDelete('cascade');
            $table->boolean('is_primary')->default(true)->comment('مدرب رئيسي');
            $table->timestamps();
            
            $table->unique(['daily_session_id', 'instructor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_instructors');
    }
};
