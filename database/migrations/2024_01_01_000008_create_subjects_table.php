<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول المواد الدراسية
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('كود المادة');
            $table->string('name')->comment('اسم المادة');
            $table->string('name_en')->nullable()->comment('الاسم بالإنجليزية');
            $table->text('description')->nullable()->comment('الوصف');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
