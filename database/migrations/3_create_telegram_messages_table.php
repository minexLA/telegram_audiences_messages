<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('related');
            $table->string('type', 20)->default('mass')->index();
            $table->string('trigger', 100)->index()->nullable();
            $table->string('text', 5000);
            $table->string('send_status')->default('scheduled')->index();
            $table->dateTime('send_at')->index()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
