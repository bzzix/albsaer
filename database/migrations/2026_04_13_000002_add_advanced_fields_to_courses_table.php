<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('description');
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            $table->text('short_description')->nullable()->after('name');
            $table->longText('content')->nullable()->after('short_description');
            
            $table->string('level', 50)->nullable()->comment('beginner, intermediate, advanced')->after('content');
            $table->string('language', 50)->nullable()->default('ar')->after('level');
            $table->integer('enrollment_limit')->nullable()->after('language');
            
            $table->json('requirements')->nullable()->after('enrollment_limit');
            $table->json('learning_outcomes')->nullable()->after('requirements');
            
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('instructor_id');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
            $table->dropForeign(['created_by']);
            
            $table->dropColumn([
                'price', 
                'sale_price', 
                'short_description', 
                'content', 
                'level', 
                'language', 
                'enrollment_limit', 
                'requirements', 
                'learning_outcomes',
                'instructor_id',
                'created_by'
            ]);
        });
    }
};
