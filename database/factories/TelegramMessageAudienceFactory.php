<?php

namespace Minex\TelegramAudiencesMessages\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Minex\TelegramAudiencesMessages\TelegramAudience;
use Minex\TelegramAudiencesMessages\TelegramMessage;
use Minex\TelegramAudiencesMessages\TelegramMessageAudience;

class TelegramMessageAudienceFactory extends Factory
{
    protected $model = TelegramMessageAudience::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'message_id' => TelegramMessage::factory(),
            'audience_id' => TelegramAudience::factory(),
        ];
    }
}
