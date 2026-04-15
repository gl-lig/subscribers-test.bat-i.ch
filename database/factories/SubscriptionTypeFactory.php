<?php

namespace Database\Factories;

use App\Models\SubscriptionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionTypeFactory extends Factory
{
    protected $model = SubscriptionType::class;

    public function definition(): array
    {
        return [
            'status' => 'online',
            'sort_order' => 0,
            'parcelles_count' => fake()->numberBetween(1, 100),
            'parcelles_unlimited' => false,
            'alertes_count' => fake()->numberBetween(1, 50),
            'stockage_go' => fake()->numberBetween(1, 100),
            'stockage_unlimited' => false,
            'cloud_externe' => false,
            'lot_sauvegarde' => false,
            'veille_robotisee' => false,
            'veille_count' => 0,
            'veille_unlimited' => false,
            'workspace_enabled' => false,
            'workspace_count' => 0,
            'workspace_unlimited' => false,
            'price_chf' => fake()->randomFloat(2, 49, 349),
            'is_free' => false,
            'is_default' => false,
            'discount_24_months' => 0,
            'discount_36_months' => 0,
        ];
    }

    public function free(): static
    {
        return $this->state(['price_chf' => 0, 'is_free' => true]);
    }

    public function withDiscounts(float $d24 = 10, float $d36 = 15): static
    {
        return $this->state(['discount_24_months' => $d24, 'discount_36_months' => $d36]);
    }

    public function default(): static
    {
        return $this->state(['is_default' => true]);
    }
}
