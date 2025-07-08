<?php

namespace Minex\TelegramAudiencesMessages\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Minex\TelegramAudiencesMessages\TelegramMessage;
use Minex\TelegramAudiencesMessages\TelegramMessageMedia;

class TelegramMessageMediaFactory extends Factory
{
    protected $model = TelegramMessageMedia::class;

    public function definition(): array
    {
        $types = [
            'image',
            'video',
        ];

        return [
            'path' => $this->faker->word(),
            'type' => $this->faker->randomElement($types),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'message_id' => TelegramMessage::factory(),
        ];
    }
}
