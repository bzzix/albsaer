<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول سجلات واتساب
     */
    public function up(): void
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_phone', 20);
            $table->string('message_type', 100);
            $table->text('message_content');
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed'])->default('pending');
            $table->json('response_data')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
            
            $table->index('recipient_phone');
            $table->index('message_type');
            $table->index('status');
            $table->index('sent_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
