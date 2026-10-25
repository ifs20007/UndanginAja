<?php

namespace Tests\Feature;

use App\Enums\GuestAttendance;
use App\Enums\TemplateCategory;
use App\Enums\TransactionStatus;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationGallery;
use App\Models\Plan;
use App\Models\Template;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VoiceComment;
use Database\Seeders\PlanSeeder;
use Database\Seeders\TemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_models_and_relationships_work_correctly(): void
    {
        $user = User::factory()->create([
            'subscription_plan' => 'pro',
            'subscription_expires_at' => now()->addMonth(),
        ]);

        $this->assertTrue($user->hasActiveSubscription());

        $plan = Plan::factory()->create([
            'price' => 99000,
            'is_popular' => true,
            'max_invitations' => 5,
        ]);
        $this->assertIsArray($plan->features);
        $this->assertIsInt($plan->price);
        $this->assertEquals('Rp 99.000', $plan->formatted_price);
        $this->assertTrue(Plan::popular()->exists());

        $template = Template::factory()->create([
            'category' => TemplateCategory::Wedding,
            'is_premium' => false,
        ]);
        $this->assertInstanceOf(TemplateCategory::class, $template->category);
        $this->assertTrue(Template::free()->exists());

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => TransactionStatus::Success,
        ]);
        $this->assertInstanceOf(TransactionStatus::class, $transaction->status);
        $this->assertTrue($transaction->user->is($user));
        $this->assertTrue($transaction->plan->is($plan));
        $this->assertTrue($user->transactions->contains($transaction));
        $this->assertTrue($plan->transactions->contains($transaction));

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'is_published' => true,
        ]);
        $this->assertTrue($invitation->user->is($user));
        $this->assertTrue($invitation->template->is($template));
        $this->assertTrue($user->invitations->contains($invitation));
        $this->assertTrue($template->invitations->contains($invitation));
        $this->assertNotEmpty($invitation->couple_name);
        $this->assertStringContainsString('/undangan/', $invitation->public_url);
        $this->assertTrue(Invitation::published()->exists());

        $guest = Guest::factory()->create([
            'invitation_id' => $invitation->id,
            'attendance' => GuestAttendance::Confirmed,
        ]);
        $this->assertInstanceOf(GuestAttendance::class, $guest->attendance);
        $this->assertTrue($guest->invitation->is($invitation));
        $this->assertTrue($invitation->guests->contains($guest));
        $this->assertTrue(Guest::confirmed()->exists());

        $gallery = InvitationGallery::factory()->create([
            'invitation_id' => $invitation->id,
        ]);
        $this->assertTrue($gallery->invitation->is($invitation));
        $this->assertTrue($invitation->galleries->contains($gallery));

        $voiceComment = VoiceComment::factory()->create([
            'guest_id' => $guest->id,
            'invitation_id' => $invitation->id,
        ]);
        $this->assertTrue($voiceComment->guest->is($guest));
        $this->assertTrue($voiceComment->invitation->is($invitation));
        $this->assertTrue($guest->voiceComment->is($voiceComment));
        $this->assertTrue($invitation->voiceComments->contains($voiceComment));
        $this->assertTrue($guest->hasVoiceComment());
        $this->assertNotEmpty($voiceComment->audio_url);
        $this->assertTrue(VoiceComment::approved()->exists());

        // Soft deletes test
        $invitationId = $invitation->id;
        $invitation->delete();
        $this->assertSoftDeleted('invitations', ['id' => $invitationId]);
    }

    public function test_seeders_populate_plans_and_templates_properly(): void
    {
        $this->seed(PlanSeeder::class);
        $this->seed(TemplateSeeder::class);

        $this->assertDatabaseHas('plans', ['slug' => 'free']);
        $this->assertDatabaseHas('plans', ['slug' => 'pro']);
        $this->assertDatabaseHas('plans', ['slug' => 'business']);

        $this->assertDatabaseHas('templates', ['slug' => 'elegant-rose']);
        $this->assertDatabaseHas('templates', ['slug' => 'royal-gold']);
    }

    public function test_authenticated_routes_render_successfully(): void
    {
        $this->seed(PlanSeeder::class);
        $this->seed(TemplateSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('invitations.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('templates.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get(route('subscription.index'));
        $response->assertStatus(200);
    }

    public function test_public_invitation_and_rsvp_flow(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create();

        $invitation = Invitation::factory()->create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'slug' => 'john-dan-jane',
            'is_published' => true,
        ]);

        $response = $this->get(route('public.invitation', 'john-dan-jane'));
        $response->assertStatus(200);

        $rsvpResponse = $this->postJson(route('public.rsvp', 'john-dan-jane'), [
            'name' => 'Budi Santoso',
            'phone' => '08123456789',
            'attendance' => 'confirmed',
            'message' => 'Selamat menempuh hidup baru!',
        ]);

        $rsvpResponse->assertStatus(200);
        $rsvpResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $invitation->id,
            'name' => 'Budi Santoso',
            'attendance' => 'confirmed',
        ]);
    }

    public function test_interactive_features_and_plan_feature_gating(): void
    {
        $this->seed(PlanSeeder::class);

        // Test Free tier gating
        $freeUser = User::factory()->create([
            'subscription_plan' => 'free',
            'subscription_expires_at' => null,
        ]);
        $this->assertTrue($freeUser->hasFeature('countdown_timer'));
        $this->assertTrue($freeUser->hasFeature('basic_rsvp'));
        $this->assertFalse($freeUser->hasFeature('custom_domain'));
        $this->assertFalse($freeUser->hasFeature('live_streaming'));
        $this->assertFalse($freeUser->hasFeature('love_story'));
        $this->assertFalse($freeUser->hasFeature('voice_comment'));

        // Test Pro tier gating
        $proUser = User::factory()->create([
            'subscription_plan' => 'pro',
            'subscription_expires_at' => now()->addMonth(),
        ]);
        $this->assertTrue($proUser->hasFeature('countdown_timer'));
        $this->assertTrue($proUser->hasFeature('custom_domain'));
        $this->assertTrue($proUser->hasFeature('live_streaming'));
        $this->assertTrue($proUser->hasFeature('dresscode'));
        $this->assertTrue($proUser->hasFeature('custom_font'));
        $this->assertTrue($proUser->hasFeature('background_music'));
        $this->assertFalse($proUser->hasFeature('love_story'));
        $this->assertFalse($proUser->hasFeature('voice_comment'));
        $this->assertFalse($proUser->hasFeature('white_label'));

        // Test Business tier gating
        $businessUser = User::factory()->create([
            'subscription_plan' => 'business',
            'subscription_expires_at' => now()->addYear(),
        ]);
        $this->assertTrue($businessUser->hasFeature('custom_domain'));
        $this->assertTrue($businessUser->hasFeature('live_streaming'));
        $this->assertTrue($businessUser->hasFeature('love_story'));
        $this->assertTrue($businessUser->hasFeature('voice_comment'));
        $this->assertTrue($businessUser->hasFeature('white_label'));
        $this->assertTrue($businessUser->hasFeature('guest_on_screen'));
        $this->assertTrue($businessUser->hasFeature('wa_blast'));

        // Test Invitation interactive attributes
        $invitation = Invitation::factory()->create([
            'user_id' => $businessUser->id,
            'live_streaming_url' => 'https://youtube.com/live/xyz123',
            'music_url' => 'https://example.com/audio/wedding.mp3',
            'dresscode' => 'Sage Green / Earth Tone',
            'custom_font' => 'Playfair Display',
            'love_stories' => [
                ['year' => '2021', 'title' => 'Pertemuan', 'story' => 'Bertemu di Jakarta'],
            ],
        ]);

        $this->assertTrue($invitation->hasLiveStreaming());
        $this->assertTrue($invitation->hasBackgroundMusic());
        $this->assertTrue($invitation->hasLoveStories());
        $this->assertEquals('Sage Green / Earth Tone', $invitation->dresscode_text);
        $this->assertIsArray($invitation->love_stories);
        $this->assertCount(1, $invitation->love_stories);
    }
}
