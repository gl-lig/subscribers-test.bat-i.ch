<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
            'notify_new_order' => true,
        ];
    }

    public function superAdmin(): static
    {
        return $this->state(['role' => 'super_admin']);
    }

    public function apiUser(): static
    {
        return $this->state(['role' => 'api_user']);
    }

    public function inactive(): static
    {
        return $this->state(['status' => 'inactive']);
    }
}
