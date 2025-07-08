<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Minex\TelegramAudiencesMessages\TelegramAudience;

class TelegramAudienceFactory extends Factory
{
    protected $model = TelegramAudience::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
