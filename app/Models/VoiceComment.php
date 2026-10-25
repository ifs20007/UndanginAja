<?php

namespace App\Models;

use Database\Factories\VoiceCommentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VoiceComment extends Model
{
    /** @use HasFactory<VoiceCommentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'guest_id',
        'invitation_id',
        'audio_path',
        'duration',
        'file_size',
        'mime_type',
        'is_approved',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'duration' => 'integer',
            'file_size' => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Get the guest who recorded this voice comment.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the invitation this voice comment belongs to.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    /**
     * Scope a query to only include approved voice comments.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    /**
     * Get audio playback URL.
     */
    public function getAudioUrlAttribute(): string
    {
        if (filter_var($this->audio_path, FILTER_VALIDATE_URL)) {
            return $this->audio_path;
        }

        return Storage::disk('public')->url($this->audio_path);
    }
}
