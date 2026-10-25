<?php

namespace App\Models;

use App\Enums\GuestAttendance;
use Database\Factories\GuestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guest extends Model
{
    /** @use HasFactory<GuestFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invitation_id',
        'name',
        'phone',
        'attendance',
        'message',
        'responded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'attendance' => GuestAttendance::class,
            'responded_at' => 'datetime',
        ];
    }

    /**
     * Get the invitation that the guest belongs to.
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    /**
     * Get the voice comment associated with the guest.
     */
    public function voiceComment(): HasOne
    {
        return $this->hasOne(VoiceComment::class);
    }

    /**
     * Get all voice comments for the guest.
     */
    public function voiceComments(): HasMany
    {
        return $this->hasMany(VoiceComment::class);
    }

    /**
     * Check if the guest has recorded a voice comment.
     */
    public function hasVoiceComment(): bool
    {
        return $this->voiceComment()->exists();
    }

    /**
     * Scope a query to only include confirmed/attending guests.
     */
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->whereIn('attendance', ['confirmed', 'attending']);
    }

    /**
     * Scope a query to only include declined/not attending guests.
     */
    public function scopeDeclined(Builder $query): Builder
    {
        return $query->whereIn('attendance', ['declined', 'not_attending']);
    }

    /**
     * Scope a query to only include pending guests.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('attendance', 'pending');
    }
}
