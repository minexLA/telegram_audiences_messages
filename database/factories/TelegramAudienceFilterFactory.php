<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Minex\TelegramAudiencesMessages\TelegramAudience;
use Minex\TelegramAudiencesMessages\TelegramAudienceFilter;

class TelegramAudienceFilterFactory extends Factory
{
    protected $model = TelegramAudienceFilter::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'data' => [],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'audience_id' => TelegramAudience::factory(),
        ];
    }
}
