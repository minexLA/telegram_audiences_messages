<?php

namespace Minex\TelegramAudiencesMessages\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Minex\TelegramAudiencesMessages\TelegramMessage;
use Minex\TelegramAudiencesMessages\TelegramMessageButton;

class TelegramMessageButtonFactory extends Factory
{
    protected $model = TelegramMessageButton::class;

    public function definition(): array
    {
        $types = [
            'link'
        ];

        return [
            'label' => $this->faker->word(),
            'content' => $this->faker->word(),
            'type' => $this->faker->randomElement($types),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'message_id' => TelegramMessage::factory(),
        ];
    }
}
