<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول السنوات الأكاديمية
     */
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('اسم السنة (2025-2026)');
            $table->string('code', 20)->unique()->comment('كود السنة');
            $table->date('start_date')->comment('تاريخ البداية');
            $table->date('end_date')->comment('تاريخ النهاية');
            $table->boolean('is_active')->default(false)->comment('السنة النشطة');
            $table->timestamps();
            
            $table->index('is_active');
            // $table->rawCheck('end_date > start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
