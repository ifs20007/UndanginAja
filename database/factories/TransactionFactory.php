<?php

namespace Database\Factories;

use App\Enums\TransactionStatus;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(TransactionStatus::cases());

        return [
            'user_id' => User::factory(),
            'plan_id' => Plan::factory(),
            'order_id' => 'INV-'.strtoupper(Str::random(10)),
            'snap_token' => Str::random(36),
            'amount' => fake()->randomElement([49000, 99000, 149000]),
            'status' => $status,
            'paid_at' => $status === TransactionStatus::Success ? now() : null,
        ];
    }
}
