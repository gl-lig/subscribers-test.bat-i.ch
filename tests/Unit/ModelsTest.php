<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Models\SubscriptionType;
use App\Models\SubscriptionTypeTranslation;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    // --- Admin ---

    public function test_admin_is_super(): void
    {
        $admin = Admin::factory()->superAdmin()->create();
        $this->assertTrue($admin->isSuper());
        $this->assertFalse($admin->isApiUser());
    }

    public function test_admin_is_api_user(): void
    {
        $admin = Admin::factory()->apiUser()->create();
        $this->assertTrue($admin->isApiUser());
        $this->assertFalse($admin->isSuper());
    }

    public function test_admin_is_active(): void
    {
        $active = Admin::factory()->create(['status' => 'active']);
        $inactive = Admin::factory()->create(['status' => 'inactive']);

        $this->assertTrue($active->isActive());
        $this->assertFalse($inactive->isActive());
    }

    public function test_admin_full_name(): void
    {
        $admin = Admin::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $this->assertEquals('John Doe', $admin->full_name);
    }

    public function test_admin_has_two_factor(): void
    {
        $without = Admin::factory()->create();
        $with = Admin::factory()->create(['two_factor_confirmed_at' => now()]);

        $this->assertFalse($without->hasTwoFactor());
        $this->assertTrue($with->hasTwoFactor());
    }

    public function test_admin_scope_active(): void
    {
        Admin::factory()->create(['status' => 'active']);
        Admin::factory()->create(['status' => 'inactive']);

        $this->assertEquals(1, Admin::active()->count());
    }

    public function test_admin_scope_notifiable(): void
    {
        Admin::factory()->create(['status' => 'active', 'notify_new_order' => true]);
        Admin::factory()->create(['status' => 'active', 'notify_new_order' => false]);
        Admin::factory()->create(['status' => 'inactive', 'notify_new_order' => true]);

        $this->assertEquals(1, Admin::notifiable()->count());
    }

    // --- Order ---

    public function test_order_is_active(): void
    {
        $active = Order::factory()->create();
        $expired = Order::factory()->expired()->create();

        $this->assertTrue($active->isActive());
        $this->assertFalse($expired->isActive());
    }

    public function test_order_is_expired(): void
    {
        $expired = Order::factory()->expired()->create();
        $this->assertTrue($expired->isExpired());
    }

    public function test_order_days_remaining(): void
    {
        $order = Order::factory()->create([
            'expires_at' => now()->addDays(30)->toDateString(),
        ]);

        $this->assertGreaterThanOrEqual(29, $order->daysRemaining());
        $this->assertLessThanOrEqual(31, $order->daysRemaining());
    }

    public function test_order_days_remaining_expired(): void
    {
        $order = Order::factory()->expired()->create();
        $this->assertEquals(0, $order->daysRemaining());
    }

    public function test_order_calculate_prorata(): void
    {
        $order = Order::factory()->create([
            'price_paid' => 365.00,
            'status' => 'active',
            'starts_at' => now()->subDays(100)->toDateString(),
            'expires_at' => now()->addDays(265)->toDateString(),
        ]);

        $prorata = $order->calculateProrata();
        $this->assertGreaterThan(0, $prorata);
        $this->assertLessThan(365.00, $prorata);
    }

    public function test_order_calculate_prorata_returns_zero_for_expired(): void
    {
        $order = Order::factory()->expired()->create();
        $this->assertEquals(0, $order->calculateProrata());
    }

    public function test_order_generate_order_number(): void
    {
        $number = Order::generateOrderNumber();
        $this->assertMatchesRegularExpression('/^CMD-\d{6}$/', $number);
    }

    public function test_order_generate_order_number_increments(): void
    {
        Order::factory()->create(['order_number' => 'CMD-000042']);
        $next = Order::generateOrderNumber();
        $this->assertEquals('CMD-000043', $next);
    }

    public function test_order_relationships(): void
    {
        $subscriber = Subscriber::factory()->create();
        $type = SubscriptionType::factory()->create();
        $order = Order::factory()->create([
            'subscriber_id' => $subscriber->id,
            'subscription_type_id' => $type->id,
        ]);

        $this->assertEquals($subscriber->id, $order->subscriber->id);
        $this->assertEquals($type->id, $order->subscriptionType->id);
    }

    // --- Subscriber ---

    public function test_subscriber_active_order(): void
    {
        $subscriber = Subscriber::factory()->create();

        // No orders
        $this->assertNull($subscriber->activeOrder());

        // Active order
        $active = Order::factory()->create([
            'subscriber_id' => $subscriber->id,
            'status' => 'active',
            'expires_at' => now()->addYear()->toDateString(),
        ]);

        $this->assertEquals($active->id, $subscriber->activeOrder()->id);
    }

    public function test_subscriber_active_order_ignores_expired(): void
    {
        $subscriber = Subscriber::factory()->create();
        Order::factory()->expired()->create(['subscriber_id' => $subscriber->id]);

        $this->assertNull($subscriber->activeOrder());
    }

    // --- SubscriptionType ---

    public function test_subscription_type_discount_for_duration(): void
    {
        $type = SubscriptionType::factory()->withDiscounts(10, 15)->create();

        $this->assertEquals(0, $type->discountForDuration(12));
        $this->assertEquals(10, $type->discountForDuration(24));
        $this->assertEquals(15, $type->discountForDuration(36));
    }

    public function test_subscription_type_price_for_duration(): void
    {
        $type = SubscriptionType::factory()->withDiscounts(10, 0)->create(['price_chf' => 100.00]);

        $this->assertEquals(100.00, $type->priceForDuration(12));
        $this->assertEquals(180.00, $type->priceForDuration(24)); // 200 - 10%
    }

    public function test_subscription_type_scope_online(): void
    {
        SubscriptionType::factory()->create(['status' => 'online']);
        SubscriptionType::factory()->create(['status' => 'inactive']);

        $this->assertEquals(1, SubscriptionType::online()->count());
    }

    public function test_subscription_type_translation(): void
    {
        $type = SubscriptionType::factory()->create();
        SubscriptionTypeTranslation::create([
            'subscription_type_id' => $type->id,
            'locale' => 'fr',
            'name' => 'Premium',
            'description' => 'Description',
        ]);
        $type->load('translations');

        $this->assertEquals('Premium', $type->translation('fr')->name);
    }

    public function test_subscription_type_translation_fallback_fr(): void
    {
        $type = SubscriptionType::factory()->create();
        SubscriptionTypeTranslation::create([
            'subscription_type_id' => $type->id,
            'locale' => 'fr',
            'name' => 'Premium FR',
        ]);
        $type->load('translations');

        // Requesting 'de' should fallback to 'fr'
        $this->assertEquals('Premium FR', $type->translation('de')->name);
    }

    // --- PromoCode ---

    public function test_promo_code_is_valid(): void
    {
        $promo = PromoCode::factory()->create();
        $this->assertTrue($promo->isValid());
    }

    public function test_promo_code_inactive(): void
    {
        $promo = PromoCode::factory()->inactive()->create();
        $this->assertFalse($promo->isValid());
    }

    public function test_promo_code_expired(): void
    {
        $promo = PromoCode::factory()->expired()->create();
        $this->assertFalse($promo->isValid());
    }

    public function test_promo_code_not_yet_valid(): void
    {
        $promo = PromoCode::factory()->create(['valid_from' => now()->addMonth()]);
        $this->assertFalse($promo->isValid());
    }

    public function test_promo_code_specific_user(): void
    {
        $promo = PromoCode::factory()->forUser('@user1')->create();

        $this->assertTrue($promo->isValid('@user1'));
        $this->assertFalse($promo->isValid('@user2'));
    }

    // --- Setting ---

    public function test_setting_get_set(): void
    {
        Setting::set('test_key', 'test_value');
        $this->assertEquals('test_value', Setting::get('test_key'));
    }

    public function test_setting_get_default(): void
    {
        $this->assertEquals('default', Setting::get('nonexistent', 'default'));
    }

    public function test_setting_update(): void
    {
        Setting::set('key', 'value1');
        Setting::set('key', 'value2');
        $this->assertEquals('value2', Setting::get('key'));
    }
}
