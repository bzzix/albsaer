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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('code')->unique()->after('id')->comment('رمز المشروع');
            $table->boolean('is_active')->default(true)->after('status');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->string('code')->unique()->after('id')->comment('رمز المجموعة');
            $table->boolean('is_active')->default(true)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['code', 'is_active']);
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['code', 'is_active']);
        });
    }
};
