<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_message_audiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('telegram_messages')->cascadeOnDelete();
            $table->foreignId('audience_id')->constrained('telegram_audiences')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_message_audiences');
    }
};
