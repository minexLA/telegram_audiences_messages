<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_message_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('telegram_messages')->cascadeOnDelete();
            $table->string('path', 255);
            $table->string('type', 20)->default('image');
            $table->string('telegram_file_id', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_message_media');
    }
};
