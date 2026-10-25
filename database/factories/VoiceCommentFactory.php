<?php

namespace Database\Factories;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\VoiceComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VoiceComment>
 */
class VoiceCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guest_id' => Guest::factory(),
            'invitation_id' => fn (array $attributes) => Guest::find($attributes['guest_id'])?->invitation_id ?? Invitation::factory(),
            'audio_path' => 'voice-comments/sample-'.fake()->uuid().'.webm',
            'duration' => fake()->numberBetween(3, 45),
            'file_size' => fake()->numberBetween(10000, 200000),
            'mime_type' => 'audio/webm',
            'is_approved' => true,
        ];
    }
}
