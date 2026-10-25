<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'subscription_plan', 'subscription_expires_at', 'wa_blast_quota'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'subscription_plan',
        'subscription_expires_at',
        'wa_blast_quota',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'subscription_expires_at' => 'datetime',
            'wa_blast_quota' => 'integer',
        ];
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the invitations created by the user.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Check if user has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        if (! $this->subscription_plan || ! $this->subscription_expires_at) {
            return false;
        }

        return $this->subscription_expires_at->isFuture();
    }

    /**
     * Get current user subscription plan.
     */
    public function getCurrentPlanAttribute(): ?Plan
    {
        $slug = 'free';

        if ($this->subscription_plan === 'free') {
            $slug = 'free';
        } elseif ($this->hasActiveSubscription()) {
            $slug = $this->subscription_plan;
        }

        return Plan::where('slug', $slug)->first() ?? Plan::where('slug', 'free')->first();
    }

    /**
     * Check if user has access to a specific feature based on current subscription plan.
     */
    public function hasFeature(string $feature): bool
    {
        $plan = $this->current_plan;

        if (! $plan) {
            return false;
        }

        return $plan->hasFeature($feature);
    }

    /**
     * Check if user is allowed to create another invitation based on plan limits.
     */
    public function canCreateInvitation(): bool
    {
        $plan = $this->current_plan;

        if (! $plan) {
            return false;
        }

        return $this->invitations()->count() < $plan->max_invitations;
    }

    /**
     * Get remaining invitation quota for user.
     */
    public function getRemainingInvitationsAttribute(): int
    {
        $plan = $this->current_plan;

        if (! $plan) {
            return 0;
        }

        return max(0, $plan->max_invitations - $this->invitations()->count());
    }

    /**
     * Get maximum number of gallery photos allowed based on user subscription plan.
     */
    public function getMaxGalleryPhotos(): int
    {
        $planSlug = $this->hasActiveSubscription() ? ($this->subscription_plan ?? 'free') : 'free';

        return match ($planSlug) {
            'business' => 50,
            'pro' => 6,
            default => 3,
        };
    }
}
