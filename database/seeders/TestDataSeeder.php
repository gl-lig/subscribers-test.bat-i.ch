<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::updateOrCreate(
            ['code' => 'TEST20'],
            [
                'title' => 'Code de test 20%',
                'description' => 'Code promotionnel de test offrant 20% de réduction',
                'discount_pct' => 20.00,
                'valid_from' => now(),
                'valid_until' => null,
                'usage_limit_per_user' => 1,
                'scope' => 'all',
                'is_active' => true,
            ]
        );
    }
}
