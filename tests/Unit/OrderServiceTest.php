<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Subscriber;
use App\Models\SubscriptionType;
use App\Services\OrderService;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    private OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OrderService();
    }

    public function test_calculate_price_basic(): void
    {
        $type = SubscriptionType::factory()->create(['price_chf' => 149.00]);

        $prices = $this->service->calculatePrice($type, 12);

        $this->assertEquals(149.00, $prices['price_catalogue']);
        $this->assertEquals(0, $prices['discount_duration_pct']);
        $this->assertEquals(0, $prices['discount_promo_pct']);
        $this->assertEquals(149.00, $prices['total']);
    }

    public function test_calculate_price_24_months_with_discount(): void
    {
        $type = SubscriptionType::factory()->withDiscounts(10, 15)->create(['price_chf' => 100.00]);

        $prices = $this->service->calculatePrice($type, 24);

        $this->assertEquals(200.00, $prices['price_catalogue']); // 100 * 2
        $this->assertEquals(10, $prices['discount_duration_pct']);
        $this->assertEquals(20.00, $prices['discount_duration_amount']);
        $this->assertEquals(180.00, $prices['total']);
    }

    public function test_calculate_price_36_months_with_discount(): void
    {
        $type = SubscriptionType::factory()->withDiscounts(10, 15)->create(['price_chf' => 100.00]);

        $prices = $this->service->calculatePrice($type, 36);

        $this->assertEquals(300.00, $prices['price_catalogue']); // 100 * 3
        $this->assertEquals(15, $prices['discount_duration_pct']);
        $this->assertEquals(255.00, $prices['total']);
    }

    public function test_calculate_price_with_promo_code(): void
    {
        $type = SubscriptionType::factory()->create(['price_chf' => 200.00]);
        PromoCode::factory()->create(['code' => 'TEST20', 'discount_pct' => 20]);

        $prices = $this->service->calculatePrice($type, 12, 'TEST20');

        $this->assertEquals(200.00, $prices['price_catalogue']);
        $this->assertEquals(20, $prices['discount_promo_pct']);
        $this->assertEquals(160.00, $prices['total']);
    }

    public function test_calculate_price_with_invalid_promo_code(): void
    {
        $type = SubscriptionType::factory()->create(['price_chf' => 200.00]);

        $prices = $this->service->calculatePrice($type, 12, 'INVALID');

        $this->assertEquals(0, $prices['discount_promo_pct']);
        $this->assertEquals(200.00, $prices['total']);
    }

    public function test_calculate_price_with_duration_and_promo(): void
    {
        $type = SubscriptionType::factory()->withDiscounts(10, 0)->create(['price_chf' => 100.00]);
        PromoCode::factory()->create(['code' => 'COMBO', 'discount_pct' => 20]);

        $prices = $this->service->calculatePrice($type, 24, 'COMBO');

        // 200 catalogue - 10% duration = 180 - 20% promo = 144
        $this->assertEquals(200.00, $prices['price_catalogue']);
        $this->assertEquals(10, $prices['discount_duration_pct']);
        $this->assertEquals(20, $prices['discount_promo_pct']);
        $this->assertEquals(144.00, $prices['total']);
    }

    public function test_calculate_price_with_prorata(): void
    {
        $type = SubscriptionType::factory()->create(['price_chf' => 200.00]);
        $subscriber = Subscriber::factory()->create();
        $currentOrder = Order::factory()->create([
            'subscriber_id' => $subscriber->id,
            'price_paid' => 100.00,
            'status' => 'active',
            'starts_at' => now()->subMonths(6)->toDateString(),
            'expires_at' => now()->addMonths(6)->toDateString(),
        ]);

        $prices = $this->service->calculatePrice($type, 12, null, $currentOrder);

        $this->assertGreaterThan(0, $prices['prorata']);
        $this->assertLessThan(200.00, $prices['total']);
    }

    public function test_calculate_price_total_never_negative(): void
    {
        $type = SubscriptionType::factory()->create(['price_chf' => 10.00]);
        $subscriber = Subscriber::factory()->create();
        $currentOrder = Order::factory()->create([
            'subscriber_id' => $subscriber->id,
            'price_paid' => 500.00,
            'status' => 'active',
            'starts_at' => now()->subDay()->toDateString(),
            'expires_at' => now()->addYear()->toDateString(),
        ]);

        $prices = $this->service->calculatePrice($type, 12, null, $currentOrder);

        $this->assertEquals(0, $prices['total']);
    }

    public function test_create_order(): void
    {
        $subscriber = Subscriber::factory()->create();
        $type = SubscriptionType::factory()->create(['price_chf' => 149.00]);

        $order = $this->service->createOrder($subscriber, $type, 12);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'subscriber_id' => $subscriber->id,
            'subscription_type_id' => $type->id,
            'duration_months' => 12,
            'status' => 'active',
        ]);
        $this->assertStringStartsWith('CMD-', $order->order_number);
        $this->assertNotNull($order->invoice_token);
    }

    public function test_process_upgrade_replaces_current_order(): void
    {
        $subscriber = Subscriber::factory()->create();
        $oldType = SubscriptionType::factory()->create(['price_chf' => 49.00]);
        $newType = SubscriptionType::factory()->create(['price_chf' => 149.00]);

        $oldOrder = Order::factory()->create([
            'subscriber_id' => $subscriber->id,
            'subscription_type_id' => $oldType->id,
            'status' => 'active',
            'price_paid' => 49.00,
            'starts_at' => now()->subMonths(3)->toDateString(),
            'expires_at' => now()->addMonths(9)->toDateString(),
        ]);

        $newOrder = $this->service->processUpgrade($subscriber, $newType, 12);

        $oldOrder->refresh();
        $this->assertEquals('replaced', $oldOrder->status);
        $this->assertEquals($newOrder->id, $oldOrder->replaced_by_order_id);
        $this->assertEquals('active', $newOrder->status);
    }
}
