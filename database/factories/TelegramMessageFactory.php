<?php

namespace Minex\TelegramAudiencesMessages\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Minex\TelegramAudiencesMessages\TelegramMessage;

class TelegramMessageFactory extends Factory
{
    protected $model = TelegramMessage::class;

    public function definition(): array
    {
        $types = [
            'mass',
            'trigger',
        ];
        $statuses = [
            'scheduled',
            'sent',
        ];

        return [
            'type' => $type = $this->faker->randomElement($types),
            'trigger' => $this->faker->optional((int) $type == 'trigger')->word(),
            'text' => $this->faker->text(4096),
            'send_status' => $this->faker->randomElement($statuses),
            'send_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
