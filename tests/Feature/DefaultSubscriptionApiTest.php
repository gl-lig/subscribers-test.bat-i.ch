<?php

namespace Tests\Feature;

use App\Models\SubscriptionType;
use App\Models\SubscriptionTypeTranslation;
use Tests\TestCase;

class DefaultSubscriptionApiTest extends TestCase
{
    public function test_returns_default_subscription(): void
    {
        $type = SubscriptionType::factory()->default()->create(['price_chf' => 149.00, 'status' => 'online']);
        SubscriptionTypeTranslation::create([
            'subscription_type_id' => $type->id,
            'locale' => 'fr',
            'name' => 'Premium',
            'description' => 'Le meilleur',
        ]);

        $response = $this->getJson('/api/default-subscription');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $type->id,
                'price_chf' => 149.00,
                'is_free' => false,
                'translations' => [
                    'fr' => ['name' => 'Premium', 'description' => 'Le meilleur'],
                ],
            ]);
    }

    public function test_returns_404_when_no_default(): void
    {
        SubscriptionType::factory()->create(['is_default' => false]);

        $response = $this->getJson('/api/default-subscription');

        $response->assertStatus(404)
            ->assertJson(['error' => 'no_default_configured']);
    }

    public function test_returns_all_features(): void
    {
        $type = SubscriptionType::factory()->default()->create([
            'parcelles_count' => 50,
            'parcelles_unlimited' => true,
            'cloud_externe' => true,
            'workspace_enabled' => true,
            'workspace_count' => 5,
        ]);

        $response = $this->getJson('/api/default-subscription');

        $response->assertStatus(200)
            ->assertJson([
                'parcelles_count' => 50,
                'parcelles_unlimited' => true,
                'cloud_externe' => true,
                'workspace_enabled' => true,
                'workspace_count' => 5,
            ]);
    }
}
