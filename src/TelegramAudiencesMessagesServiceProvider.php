<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Support\ServiceProvider;

class TelegramAudiencesMessagesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (app()->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__.'/../config/telegram-audiences-messages.php' => config_path('telegram-audiences-messages.php'),
            ], 'telegram_audiences_messages-config');

            // Load migrations if needed
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/telegram-audiences-messages.php', 'telegram-audiences-messages'
        );
    }
}
