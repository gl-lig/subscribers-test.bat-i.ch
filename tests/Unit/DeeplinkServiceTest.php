<?php

namespace Tests\Unit;

use App\Services\DeeplinkService;
use Tests\TestCase;

class DeeplinkServiceTest extends TestCase
{
    private DeeplinkService $service;

    protected function setUp(): void
    {
        parent::setUp();
        config(['batid.deeplink_secret' => 'test-secret-key']);
        config(['batid.deeplink_ttl' => 600]);
        $this->service = new DeeplinkService();
    }

    public function test_generate_token_returns_valid_format(): void
    {
        $token = $this->service->generateToken('+41791234567', '@testBat', 2, 12);

        $this->assertStringContains('.', $token);
        $parts = explode('.', $token);
        $this->assertCount(2, $parts);
        $this->assertEquals(64, strlen($parts[1])); // SHA256 hex = 64 chars
    }

    public function test_validate_token_returns_data_for_valid_token(): void
    {
        $token = $this->service->generateToken('+41791234567', '@testBat', 2, 24);
        $data = $this->service->validateToken($token);

        $this->assertNotNull($data);
        $this->assertEquals('+41791234567', $data['phone']);
        $this->assertEquals('@testBat', $data['bat_id']);
        $this->assertEquals(2, $data['type_id']);
        $this->assertEquals(24, $data['duration']);
    }

    public function test_validate_token_returns_null_for_tampered_token(): void
    {
        $token = $this->service->generateToken('+41791234567', '@testBat', 2);
        $tampered = $token . 'x';

        $this->assertNull($this->service->validateToken($tampered));
    }

    public function test_validate_token_returns_null_for_invalid_format(): void
    {
        $this->assertNull($this->service->validateToken('invalid'));
        $this->assertNull($this->service->validateToken(''));
        $this->assertNull($this->service->validateToken('a.b.c'));
    }

    public function test_validate_token_returns_null_when_secret_empty(): void
    {
        config(['batid.deeplink_secret' => '']);
        $service = new DeeplinkService();

        $this->assertNull($service->validateToken('some.token'));
    }

    public function test_validate_token_returns_null_for_expired_token(): void
    {
        config(['batid.deeplink_ttl' => 0]);
        $service = new DeeplinkService();

        $token = $service->generateToken('+41791234567', '@testBat', 2);
        sleep(1);

        $this->assertNull($service->validateToken($token));
    }

    public function test_generate_register_token_returns_valid_format(): void
    {
        $token = $this->service->generateRegisterToken('+41791234567', '@testBat');

        $parts = explode('.', $token);
        $this->assertCount(2, $parts);
    }

    public function test_validate_register_token_returns_data(): void
    {
        $token = $this->service->generateRegisterToken('+41791234567', '@regBat');
        $data = $this->service->validateRegisterToken($token);

        $this->assertNotNull($data);
        $this->assertEquals('+41791234567', $data['phone']);
        $this->assertEquals('@regBat', $data['bat_id']);
    }

    public function test_register_token_cannot_be_used_as_deeplink(): void
    {
        $token = $this->service->generateRegisterToken('+41791234567', '@regBat');

        $this->assertNull($this->service->validateToken($token));
    }

    public function test_deeplink_token_cannot_be_used_as_register(): void
    {
        $token = $this->service->generateToken('+41791234567', '@testBat', 2);

        $this->assertNull($this->service->validateRegisterToken($token));
    }

    public function test_validate_register_token_returns_null_for_tampered(): void
    {
        $token = $this->service->generateRegisterToken('+41791234567', '@testBat');

        $this->assertNull($this->service->validateRegisterToken($token . 'x'));
    }

    public function test_generate_webhook_token(): void
    {
        $token = $this->service->generateWebhookToken('subscription_activated', ['b' => '@bat1']);

        $parts = explode('.', $token);
        $this->assertCount(2, $parts);

        // Decode and verify content
        $json = base64_decode(strtr($parts[0], '-_', '+/'));
        $data = json_decode($json, true);

        $this->assertEquals('webhook', $data['a']);
        $this->assertEquals('subscription_activated', $data['e']);
        $this->assertEquals('@bat1', $data['b']);
    }

    public function test_default_duration_is_12(): void
    {
        $token = $this->service->generateToken('+41791234567', '@testBat', 2);
        $data = $this->service->validateToken($token);

        $this->assertEquals(12, $data['duration']);
    }

    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(str_contains($haystack, $needle));
    }
}
