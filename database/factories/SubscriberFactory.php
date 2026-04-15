<?php

namespace Database\Factories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition(): array
    {
        return [
            'bat_id' => '@' . fake()->unique()->regexify('[a-zA-Z0-9]{7}'),
            'phone' => '+4179' . fake()->numerify('#######'),
        ];
    }
}
