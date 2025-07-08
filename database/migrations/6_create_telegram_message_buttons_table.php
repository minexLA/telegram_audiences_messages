<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_message_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('telegram_messages')->cascadeOnDelete();
            $table->string('label', 100);
            $table->string('type', 60)->default('link')->index();
            $table->string('content', 4096);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_message_buttons');
    }
};
