<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('module_lessons', function (Blueprint $table) {
            $table->string('file_path', 500)->nullable()->after('video_url');
            $table->text('embed_code')->nullable()->after('file_path');
        });
    }

    public function down(): void
    {
        Schema::table('module_lessons', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'embed_code']);
        });
    }
};
