<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromoCodeFactory extends Factory
{
    protected $model = PromoCode::class;

    public function definition(): array
    {
        return [
            'title' => fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->lexify('????##')),
            'discount_pct' => fake()->randomFloat(2, 5, 50),
            'is_active' => true,
            'scope' => 'all',
            'valid_from' => now()->subMonth(),
            'valid_until' => now()->addMonth(),
        ];
    }

    public function expired(): static
    {
        return $this->state([
            'valid_from' => now()->subYear(),
            'valid_until' => now()->subDay(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function forUser(string $batId): static
    {
        return $this->state(['scope' => 'specific_user', 'bat_id_specific' => $batId]);
    }
}
