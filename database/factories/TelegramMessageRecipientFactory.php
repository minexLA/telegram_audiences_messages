<?php

namespace Minex\TelegramAudiencesMessages\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Minex\TelegramAudiencesMessages\TelegramMessage;
use Minex\TelegramAudiencesMessages\TelegramMessageRecipient;

class TelegramMessageRecipientFactory extends Factory
{
    protected $model = TelegramMessageRecipient::class;

    public function definition(): array
    {
        return [
            'user_type' => $this->faker->word(),
            'user_id' => $this->faker->word(),
            'send_status' => $this->faker->word(),
            'bot_token' => Str::random(10),
            'telegram_message_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'message_id' => TelegramMessage::factory(),
        ];
    }
}
