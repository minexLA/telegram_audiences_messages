<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_message_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('telegram_messages')->cascadeOnDelete();
            $table->morphs('user');
            $table->string('send_status', 60)->default('to_send')->index();
            $table->string('bot_token', 300)->index();
            $table->unsignedBigInteger('telegram_message_id')->nullable()->index();
            $table->timestamps();

            $table->index(['message_id', 'send_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_message_recipients');
    }
};
