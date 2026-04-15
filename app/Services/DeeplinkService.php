<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DeeplinkService
{
    private string $secret;
    private int $ttl;

    public function __construct()
    {
        $this->secret = config('batid.deeplink_secret', '');
        $this->ttl = config('batid.deeplink_ttl', 600); // 10 minutes
    }

    /**
     * Generate a deeplink token (for documentation/testing purposes).
     */
    public function generateToken(string $phone, string $batId, int $typeId, int $duration = 12): string
    {
        $payload = json_encode([
            'p' => $phone,
            'b' => $batId,
            't' => $typeId,
            'd' => $duration,
            'ts' => time(),
        ]);

        return $this->sign($payload);
    }

    /**
     * Generate a register token (for subscriber creation).
     */
    public function generateRegisterToken(string $phone, string $batId): string
    {
        $payload = json_encode([
            'a' => 'register',
            'p' => $phone,
            'b' => $batId,
            'ts' => time(),
        ]);

        return $this->sign($payload);
    }

    private function sign(string $payload): string
    {
        $encoded = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $encoded, $this->secret);

        return $encoded . '.' . $signature;
    }

    /**
     * Validate and decode a deeplink token.
     *
     * @return array{phone: string, bat_id: string, type_id: int, duration: int}|null
     */
    public function validateToken(string $token): ?array
    {
        if (empty($this->secret)) {
            Log::warning('DeeplinkService: DEEPLINK_SECRET is not configured');
            return null;
        }

        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return null;
        }

        [$encoded, $signature] = $parts;

        // Verify HMAC signature
        $expectedSignature = hash_hmac('sha256', $encoded, $this->secret);
        if (! hash_equals($expectedSignature, $signature)) {
            Log::warning('DeeplinkService: invalid signature', ['token' => substr($token, 0, 20) . '...']);
            return null;
        }

        // Decode payload
        $json = base64_decode(strtr($encoded, '-_', '+/'));
        $data = json_decode($json, true);

        if (! $data || ! isset($data['p'], $data['b'], $data['t'], $data['ts'])) {
            return null;
        }

        // Check expiration
        if ((time() - $data['ts']) > $this->ttl) {
            Log::info('DeeplinkService: token expired', ['age' => time() - $data['ts']]);
            return null;
        }

        return [
            'phone' => $data['p'],
            'bat_id' => $data['b'],
            'type_id' => (int) $data['t'],
            'duration' => (int) ($data['d'] ?? 12),
        ];
    }

    /**
     * Generate a webhook token for outgoing notifications to bat-id.
     */
    public function generateWebhookToken(string $event, array $data): string
    {
        $payload = json_encode(array_merge([
            'a' => 'webhook',
            'e' => $event,
            'ts' => time(),
        ], $data));

        return $this->sign($payload);
    }

    /**
     * Validate and decode a register token.
     *
     * @return array{phone: string, bat_id: string}|null
     */
    public function validateRegisterToken(string $token): ?array
    {
        if (empty($this->secret)) {
            Log::warning('DeeplinkService: DEEPLINK_SECRET is not configured');
            return null;
        }

        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return null;
        }

        [$encoded, $signature] = $parts;

        $expectedSignature = hash_hmac('sha256', $encoded, $this->secret);
        if (! hash_equals($expectedSignature, $signature)) {
            Log::warning('DeeplinkService: invalid register signature');
            return null;
        }

        $json = base64_decode(strtr($encoded, '-_', '+/'));
        $data = json_decode($json, true);

        if (! $data || ! isset($data['a'], $data['p'], $data['b'], $data['ts'])) {
            return null;
        }

        if ($data['a'] !== 'register') {
            return null;
        }

        if ((time() - $data['ts']) > $this->ttl) {
            Log::info('DeeplinkService: register token expired', ['age' => time() - $data['ts']]);
            return null;
        }

        return [
            'phone' => $data['p'],
            'bat_id' => $data['b'],
        ];
    }
}
