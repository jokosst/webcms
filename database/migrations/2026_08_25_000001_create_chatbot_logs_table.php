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
        Schema::create('chatbot_logs', function (Blueprint $table) {
            $table->id();
            $table->text('user_query');
            $table->text('bot_reply');
            $table->string('response_type', 40)->default('faq_database'); // ai_gemini, faq_database, small_talk, ticket_tracking, fallback_unanswered
            $table->boolean('is_answered')->default(true);
            $table->foreignId('matched_faq_id')->nullable()->constrained('faqs')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index('response_type');
            $table->index('is_answered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_logs');
    }
};
