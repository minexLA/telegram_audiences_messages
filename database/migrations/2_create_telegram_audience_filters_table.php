<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('telegram_audience_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audience_id')->constrained('telegram_audiences')->cascadeOnDelete();
            $table->string('key', 100)->index();
            $table->string('match_type', 60)->index();
            $table->text('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_audience_filters');
    }
};
