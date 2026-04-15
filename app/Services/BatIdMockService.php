<?php

namespace App\Services;

use App\Contracts\BatIdServiceInterface;
use App\Models\ApiLog;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;

class BatIdMockService implements BatIdServiceInterface
{
    public function verifyPhone(string $phone): ?array
    {
        Log::info('BatIdMockService::verifyPhone', ['phone' => $phone]);

        // If subscriber already exists with this phone, return their real bat-id
        $existing = Subscriber::where('phone', $phone)->first();
        if ($existing) {
            return [
                'bat_id' => $existing->bat_id,
                'phone' => $existing->phone,
            ];
        }

        // In mock mode, accept any valid phone number (simulates bat-id app installed)
        // A real phone number has at least 8 digits
        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) >= 8) {
            // Generate a mock bat-id for new users
            $mockBatId = '@' . substr(md5($phone . time()), 0, 7);
            return [
                'bat_id' => $mockBatId,
                'phone' => $phone,
            ];
        }

        return null;
    }

    public function notifySubscription(string $batId, string $event, array $data): bool
    {
        $deeplinkService = app(DeeplinkService::class);
        $tokenData = array_merge(['b' => $batId], $data);
        $token = $deeplinkService->generateWebhookToken($event, $tokenData);

        $webhookUrl = config('batid.webhook_url');
        $url = $webhookUrl ? rtrim($webhookUrl, '/') . '?token=' . $token : '(no webhook URL configured)';

        Log::info('BatIdMockService::notifySubscription', [
            'bat_id' => $batId,
            'event' => $event,
            'webhook_url' => $url,
        ]);

        ApiLog::create([
            'direction' => 'outgoing',
            'event' => $event,
            'bat_id' => $batId,
            'status' => 'success',
            'attempts' => 1,
            'payload' => ['bat_id' => $batId, 'event' => $event, 'data' => $data, 'webhook_url' => $url],
            'response' => ['mock' => true, 'status' => 200],
        ]);

        return true;
    }
}
