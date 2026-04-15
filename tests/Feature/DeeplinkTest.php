<?php

namespace Tests\Feature;

use App\Models\SubscriptionType;
use App\Services\DeeplinkService;
use Tests\TestCase;

class DeeplinkTest extends TestCase
{
    private DeeplinkService $deeplinkService;

    protected function setUp(): void
    {
        parent::setUp();
        config(['batid.deeplink_secret' => 'test-secret']);
        config(['batid.deeplink_ttl' => 600]);
        $this->deeplinkService = new DeeplinkService();
    }

    public function test_deeplink_with_valid_token_redirects_to_cart(): void
    {
        $type = SubscriptionType::factory()->create(['status' => 'online']);
        $token = $this->deeplinkService->generateToken('+41791234567', '@testBat', $type->id, 12);

        $response = $this->get('/deeplink?token=' . $token);

        $response->assertRedirect(route('cart'));
    }

    public function test_deeplink_sets_session_data(): void
    {
        $type = SubscriptionType::factory()->create(['status' => 'online']);
        $token = $this->deeplinkService->generateToken('+41791234567', '@testBat', $type->id, 24);

        $this->get('/deeplink?token=' . $token);

        $this->assertEquals('@testBat', session('bat_id'));
        $this->assertEquals('+41791234567', session('bat_phone'));
        $this->assertEquals($type->id, session('selected_type_id'));
        $this->assertEquals(24, session('selected_duration'));
    }

    public function test_deeplink_without_token_redirects_home(): void
    {
        $response = $this->get('/deeplink');
        $response->assertRedirect(route('home'));
    }

    public function test_deeplink_with_invalid_token_redirects_home(): void
    {
        $response = $this->get('/deeplink?token=invalid.token');
        $response->assertRedirect(route('home'));
    }

    public function test_deeplink_with_nonexistent_type_redirects_home(): void
    {
        $token = $this->deeplinkService->generateToken('+41791234567', '@testBat', 9999, 12);

        $response = $this->get('/deeplink?token=' . $token);
        $response->assertRedirect(route('home'));
    }

    public function test_deeplink_with_inactive_type_redirects_home(): void
    {
        $type = SubscriptionType::factory()->create(['status' => 'inactive']);
        $token = $this->deeplinkService->generateToken('+41791234567', '@testBat', $type->id, 12);

        $response = $this->get('/deeplink?token=' . $token);
        $response->assertRedirect(route('home'));
    }

    public function test_deeplink_normalizes_invalid_duration(): void
    {
        $type = SubscriptionType::factory()->create(['status' => 'online']);
        // Generate token with invalid duration via raw payload
        config(['batid.deeplink_secret' => 'test-secret']);
        $payload = json_encode(['p' => '+41791234567', 'b' => '@bat', 't' => $type->id, 'd' => 48, 'ts' => time()]);
        $encoded = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $encoded, 'test-secret');
        $token = $encoded . '.' . $signature;

        $this->get('/deeplink?token=' . $token);

        $this->assertEquals(12, session('selected_duration'));
    }
}
