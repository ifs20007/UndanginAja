<?php

namespace App\Models;

use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'price',
        'features',
        'max_invitations',
        'wa_blast_quota',
        'is_popular',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'features' => 'array',
            'max_invitations' => 'integer',
            'wa_blast_quota' => 'integer',
            'is_popular' => 'boolean',
        ];
    }

    /**
     * Map of feature identifiers to supported plan slugs.
     *
     * @var array<string, list<string>>
     */
    public const FEATURE_MAP = [
        // Free features (all tiers)
        'countdown_timer' => ['free', 'pro', 'business'],
        'basic_rsvp' => ['free', 'pro', 'business'],
        'manual_wa_share' => ['free', 'pro', 'business'],
        'limited_gallery' => ['free', 'pro', 'business'],

        // Pro features (pro & business)
        'custom_domain' => ['pro', 'business'],
        'all_templates' => ['pro', 'business'],
        'digital_envelope' => ['pro', 'business'],
        'rekening' => ['pro', 'business'],
        'amplop_digital' => ['pro', 'business'],
        'custom_music' => ['pro', 'business'],
        'music_url' => ['pro', 'business'],
        'background_music' => ['pro', 'business'],
        'live_streaming' => ['pro', 'business'],
        'live_streaming_url' => ['pro', 'business'],
        'dresscode' => ['pro', 'business'],
        'dresscode_text' => ['pro', 'business'],
        'custom_font' => ['pro', 'business'],
        'guest_export' => ['pro', 'business'],
        'export_guests' => ['pro', 'business'],
        'analytics' => ['pro', 'business'],

        // Business features (exclusive)
        'unlimited_invitations' => ['business'],
        'white_label' => ['business'],
        'love_story' => ['business'],
        'love_stories' => ['business'],
        'voice_comment' => ['business'],
        'voice_comments' => ['business'],
        'guest_on_screen' => ['business'],
        'wa_blast' => ['business'],
        'auto_wa_blast' => ['business'],
        'priority_support' => ['business'],
    ];

    /**
     * Check if the plan includes a specific feature.
     */
    public function hasFeature(string $feature): bool
    {
        $normalized = strtolower(trim($feature));

        // 1. Direct match in canonical feature map
        if (isset(self::FEATURE_MAP[$normalized])) {
            return in_array($this->slug, self::FEATURE_MAP[$normalized], true);
        }

        // 2. Check if text matches any item in features array
        if (is_array($this->features)) {
            $keyword = str_replace('_', ' ', $normalized);
            foreach ($this->features as $item) {
                if (str_contains(strtolower($item), $keyword)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the transactions for the plan.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope a query to only include popular plans.
     */
    public function scopePopular(Builder $query): Builder
    {
        return $query->where('is_popular', true);
    }

    /**
     * Get formatted rupiah price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }
}
