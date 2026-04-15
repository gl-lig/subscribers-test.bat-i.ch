<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Subscriber;
use App\Models\SubscriptionType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $token = Str::uuid()->toString();

        return [
            'order_number' => 'CMD-' . str_pad(fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'subscriber_id' => Subscriber::factory(),
            'subscription_type_id' => SubscriptionType::factory(),
            'duration_months' => 12,
            'price_catalogue' => 149.00,
            'discount_duration_pct' => 0,
            'promo_code' => null,
            'discount_promo_pct' => 0,
            'price_paid' => 149.00,
            'prorata_deducted' => 0,
            'status' => 'active',
            'concluded_at' => now(),
            'starts_at' => now()->toDateString(),
            'expires_at' => now()->addYear()->toDateString(),
            'invoice_token' => $token,
            'invoice_url' => config('app.url') . '/invoice/' . $token,
            'cgv_accepted_at' => now(),
            'cgv_version' => '1.0',
        ];
    }

    public function expired(): static
    {
        return $this->state([
            'status' => 'expired',
            'starts_at' => now()->subYear()->toDateString(),
            'expires_at' => now()->subDay()->toDateString(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending']);
    }
}
