<?php

namespace App\Models;

use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    /** @use HasFactory<InvitationFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'template_id',
        'slug',
        'groom_name',
        'bride_name',
        'event_date',
        'event_time',
        'venue_name',
        'venue_address',
        'google_maps_url',
        'live_streaming_url',
        'music_url',
        'dresscode',
        'custom_font',
        'love_stories',
        'message',
        'theme_config',
        'is_published',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'event_time' => 'datetime:H:i',
            'theme_config' => 'array',
            'love_stories' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the invitation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the template used by the invitation.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the guests invited to this event.
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Get the galleries for this invitation.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(InvitationGallery::class);
    }

    /**
     * Get the voice comments for this invitation.
     */
    public function voiceComments(): HasMany
    {
        return $this->hasMany(VoiceComment::class);
    }

    /**
     * Get dresscode text alias.
     */
    public function getDresscodeTextAttribute(): ?string
    {
        return $this->dresscode;
    }

    /**
     * Set dresscode text alias.
     */
    public function setDresscodeTextAttribute(?string $value): void
    {
        $this->attributes['dresscode'] = $value;
    }

    /**
     * Check if invitation has live streaming enabled.
     */
    public function hasLiveStreaming(): bool
    {
        return ! empty($this->live_streaming_url);
    }

    /**
     * Check if invitation has background music configured.
     */
    public function hasBackgroundMusic(): bool
    {
        return ! empty($this->music_url);
    }

    /**
     * Check if invitation has love stories.
     */
    public function hasLoveStories(): bool
    {
        return ! empty($this->love_stories) && is_array($this->love_stories) && count($this->love_stories) > 0;
    }

    /**
     * Scope a query to only include published invitations.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include draft invitations.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('is_published', false);
    }

    /**
     * Get formatted couple name.
     */
    public function getCoupleNameAttribute(): string
    {
        return $this->groom_name.' & '.$this->bride_name;
    }

    /**
     * Get public invitation URL.
     */
    public function getPublicUrlAttribute(): string
    {
        return url('/undangan/'.$this->slug);
    }
}
