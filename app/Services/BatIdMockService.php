<?php

namespace App\Services;

use App\Contracts\BatIdServiceInterface;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Log;

class BatIdMockService implements BatIdServiceInterface
{
    public function verifyPhone(string $phone): ?array
    {
        $mockPhone = config('batid.mock_phone');
        $mockBatId = config('batid.mock_batid');

        Log::info('BatIdMockService::verifyPhone', ['phone' => $phone, 'mock_phone' => $mockPhone]);

        if ($phone === $mockPhone) {
            return [
                'bat_id' => $mockBatId,
                'phone' => $mockPhone,
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
