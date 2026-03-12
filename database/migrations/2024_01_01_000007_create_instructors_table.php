<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول المدربين
     */
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('instructor_code', 50)->unique()->comment('كود المدرب');
            $table->string('specialization')->nullable()->comment('التخصص');
            $table->text('bio')->nullable()->comment('السيرة الذاتية');
            $table->date('hire_date')->nullable()->comment('تاريخ التعيين');
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
            
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
