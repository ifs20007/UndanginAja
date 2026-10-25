<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $groom = fake()->firstNameMale();
        $bride = fake()->firstNameFemale();
        $slug = Str::slug($groom.'-and-'.$bride.'-'.fake()->unique()->numberBetween(100, 9999));

        return [
            'user_id' => User::factory(),
            'template_id' => Template::factory(),
            'slug' => $slug,
            'groom_name' => $groom.' Pratama',
            'bride_name' => $bride.' Lestari',
            'event_date' => fake()->dateTimeBetween('+1 week', '+6 months')->format('Y-m-d'),
            'event_time' => '10:00:00',
            'venue_name' => 'Grand Ballroom Hotel '.fake()->city(),
            'venue_address' => fake()->address(),
            'google_maps_url' => 'https://maps.google.com/?q='.fake()->latitude().','.fake()->longitude(),
            'live_streaming_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'music_url' => 'https://assets.mixkit.co/music/preview/mixkit-romantic-wedding-28.mp3',
            'dresscode' => 'Batik Modern / Formal Pastel',
            'custom_font' => 'Playfair Display',
            'love_stories' => [
                [
                    'year' => '2020',
                    'title' => 'Pertemuan Pertama',
                    'story' => 'Pertama kali bertemu di perpustakaan kampus.',
                ],
                [
                    'year' => '2023',
                    'title' => 'Lamaran',
                    'story' => 'Mengikat janji suci di hadapan kedua keluarga.',
                ],
            ],
            'message' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.',
            'is_published' => fake()->boolean(80),
        ];
    }

    /**
     * Indicate that the invitation has live streaming enabled.
     */
    public function withLiveStreaming(string $url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'): static
    {
        return $this->state(fn (array $attributes) => [
            'live_streaming_url' => $url,
        ]);
    }
}
