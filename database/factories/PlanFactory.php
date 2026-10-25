<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'price' => fake()->randomElement([49000, 99000, 149000]),
            'features' => [
                'Custom domain',
                'Background music',
                'RSVP system',
                'Gallery up to 10 photos',
            ],
            'max_invitations' => fake()->numberBetween(1, 10),
            'wa_blast_quota' => fake()->randomElement([0, 50, 200, 500]),
            'is_popular' => fake()->boolean(20),
        ];
    }
}
