<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\InvitationGallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvitationGallery>
 */
class InvitationGalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invitation_id' => Invitation::factory(),
            'image_path' => 'galleries/'.fake()->uuid().'.jpg',
            'caption' => fake()->sentence(4),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
