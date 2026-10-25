<?php

namespace Database\Factories;

use App\Enums\GuestAttendance;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guest>
 */
class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attendance = fake()->randomElement(GuestAttendance::cases());

        return [
            'invitation_id' => Invitation::factory(),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'attendance' => $attendance,
            'message' => fake()->sentence(),
            'responded_at' => $attendance !== GuestAttendance::Pending ? now() : null,
        ];
    }
}
