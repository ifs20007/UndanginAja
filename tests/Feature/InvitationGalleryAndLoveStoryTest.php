<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\InvitationGallery;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InvitationGalleryAndLoveStoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'features' => ['rsvp_basic', 'countdown', 'wa_share'],
            'max_invitations' => 1,
            'wa_blast_quota' => 0,
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'price' => 49000,
            'features' => ['rsvp_basic', 'countdown', 'wa_share', 'digital_envelope', 'music', 'streaming', 'dresscode'],
            'max_invitations' => 5,
            'wa_blast_quota' => 50,
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Business',
            'slug' => 'business',
            'price' => 99000,
            'features' => ['rsvp_basic', 'countdown', 'wa_share', 'digital_envelope', 'music', 'streaming', 'dresscode', 'love_story', 'voice_comments', 'wa_blast'],
            'max_invitations' => 999,
            'wa_blast_quota' => 500,
            'is_active' => true,
        ]);
    }

    public function test_user_get_max_gallery_photos_by_tier(): void
    {
        $freeUser = User::factory()->create([
            'subscription_plan' => 'free',
            'subscription_expires_at' => null,
        ]);
        $proUser = User::factory()->create([
            'subscription_plan' => 'pro',
            'subscription_expires_at' => now()->addMonth(),
        ]);
        $businessUser = User::factory()->create([
            'subscription_plan' => 'business',
            'subscription_expires_at' => now()->addMonth(),
        ]);
        $noPlanUser = User::factory()->create([
            'subscription_plan' => null,
            'subscription_expires_at' => null,
        ]);

        $this->assertSame(3, $freeUser->getMaxGalleryPhotos());
        $this->assertSame(6, $proUser->getMaxGalleryPhotos());
        $this->assertSame(50, $businessUser->getMaxGalleryPhotos());
        $this->assertSame(3, $noPlanUser->getMaxGalleryPhotos());
    }

    public function test_free_user_can_upload_within_limit_and_rejected_when_exceeding(): void
    {
        $user = User::factory()->create([
            'subscription_plan' => 'free',
            'subscription_expires_at' => null,
        ]);

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'title' => 'Pernikahan Budi & Ani',
            'slug' => 'budi-dan-ani',
            'groom_name' => 'Budi',
            'bride_name' => 'Ani',
            'event_date' => now()->addDays(10),
            'event_time' => '10:00:00',
            'venue_name' => 'Gedung Serbaguna',
            'venue_address' => 'Jl. Mawar No. 10',
            'is_published' => true,
        ]);

        $this->actingAs($user);

        // Upload 2 photos (allowed, total 2 <= 3)
        $response = $this->post(route('invitations.galleries.store', $invitation), [
            'photos' => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertCount(2, $invitation->galleries()->get());

        // Attempt to upload 2 more photos (2 + 2 = 4 > 3, rejected)
        $responseExceed = $this->post(route('invitations.galleries.store', $invitation), [
            'photos' => [
                UploadedFile::fake()->image('photo3.jpg'),
                UploadedFile::fake()->image('photo4.jpg'),
            ],
        ]);

        $responseExceed->assertSessionHasErrors('photos');
        $this->assertCount(2, $invitation->galleries()->get());
    }

    public function test_user_can_delete_gallery_photo(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::create([
            'user_id' => $user->id,
            'title' => 'Pernikahan Budi & Ani',
            'slug' => 'budi-dan-ani-del',
            'groom_name' => 'Budi',
            'bride_name' => 'Ani',
            'event_date' => now()->addDays(10),
            'event_time' => '10:00:00',
            'venue_name' => 'Gedung Serbaguna',
            'venue_address' => 'Jl. Mawar No. 10',
            'is_published' => true,
        ]);

        $file = UploadedFile::fake()->image('test.jpg');
        $path = $file->store('invitations/'.$invitation->id.'/gallery', 'public');

        $gallery = InvitationGallery::create([
            'invitation_id' => $invitation->id,
            'image_path' => $path,
            'caption' => 'Momen Indah',
            'order' => 1,
        ]);

        Storage::disk('public')->assertExists($path);

        $this->actingAs($user);
        $response = $this->from(route('invitations.edit', $invitation))
            ->delete(route('invitations.galleries.destroy', [$invitation, $gallery]));

        $response->assertRedirect(route('invitations.edit', $invitation));
        $this->assertDatabaseMissing('invitation_galleries', ['id' => $gallery->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_love_story_and_advanced_settings_render_on_public_page(): void
    {
        $user = User::factory()->create([
            'subscription_plan' => 'business',
            'subscription_expires_at' => now()->addMonth(),
        ]);

        $loveStories = [
            [
                'date' => '2020-01-15',
                'title' => 'Pertemuan Pertama',
                'story' => 'Bertemu di perpustakaan kampus saat membaca buku.',
            ],
            [
                'date' => '2023-06-20',
                'title' => 'Lamaran Romantis',
                'story' => 'Melamar di puncak bukit saat matahari terbenam.',
            ],
        ];

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'title' => 'Pernikahan Rizky & Citra',
            'slug' => 'rizky-dan-citra',
            'groom_name' => 'Rizky Pratama',
            'bride_name' => 'Citra Lestari',
            'event_date' => now()->addDays(30),
            'event_time' => '09:00:00',
            'venue_name' => 'Grand Ballroom',
            'venue_address' => 'Jl. Jenderal Sudirman No. 1',
            'love_stories' => $loveStories,
            'music_url' => 'https://example.com/audio.mp3',
            'dresscode' => 'Batik Modern / Pastel Cream',
            'live_streaming_url' => 'https://youtube.com/live/demo123',
            'custom_font' => 'Cinzel',
            'theme_config' => [
                'groom_nickname' => 'Rizky',
                'groom_order' => 'Putra Pertama dari',
                'groom_father' => 'Bpk. Hendra Pratama',
                'groom_mother' => 'Ibu Sri Wahyuni',
                'bride_nickname' => 'Citra',
                'bride_order' => 'Putri Kedua dari',
                'bride_father' => 'Bpk. Bambang Wijaya',
                'bride_mother' => 'Ibu Ratna Dewi',
                'akad_time' => '08:00 WIB - 10:00 WIB',
                'resepsi_time' => '11:00 WIB - 14:00 WIB',
                'bank_accounts' => [
                    [
                        'bank_name' => 'BCA',
                        'account_number' => '1234567890',
                        'account_holder' => 'Rizky Pratama',
                    ],
                ],
            ],
            'is_published' => true,
        ]);

        InvitationGallery::create([
            'invitation_id' => $invitation->id,
            'image_path' => 'demo/gallery1.jpg',
            'caption' => 'Prewedding di Bromo',
            'order' => 1,
        ]);

        $response = $this->get(route('public.invitation', $invitation->slug));

        $response->assertStatus(200);

        // Assert Love Stories rendered
        $response->assertSee('Kisah Cinta Kami');
        $response->assertSee('Pertemuan Pertama');
        $response->assertSee('Lamaran Romantis');

        // Assert Gallery rendered
        $response->assertSee('Potret Kebahagiaan Kami');
        $response->assertSee('Prewedding di Bromo');

        // Assert Advanced Settings rendered
        $response->assertSee('Bpk. Hendra Pratama');
        $response->assertSee('Ibu Sri Wahyuni');
        $response->assertSee('Bpk. Bambang Wijaya');
        $response->assertSee('Ibu Ratna Dewi');
        $response->assertSee('08:00 WIB - 10:00 WIB');
        $response->assertSee('11:00 WIB - 14:00 WIB');
        $response->assertSee('Batik Modern / Pastel Cream');
        $response->assertSee('https://youtube.com/live/demo123');
        $response->assertSee('1234567890');
        $response->assertSee('BCA');
    }
}
