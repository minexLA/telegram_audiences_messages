<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_message_media', function (Blueprint $table) {
            $table->after('type', function (Blueprint $table) {
                $table->unsignedSmallInteger('duration')->nullable();
                $table->unsignedSmallInteger('width')->nullable();
                $table->unsignedSmallInteger('height')->nullable();
                $table->string('thumbnail_path', 100)->nullable();
            });
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_message_recipients');
    }
};
