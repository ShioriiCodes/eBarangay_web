<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'related_type',
        'related_id',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForCurrentUser(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id());
    }

    public function getRelativeTimeAttribute(): string
    {
        return $this->created_at instanceof Carbon
            ? $this->created_at->diffForHumans()
            : '';
    }

    public function getLinkAttribute(): ?string
    {
        if (! $this->related_type || ! $this->related_id) {
            return null;
        }

        if ($this->related_type === DocumentRequest::class) {
            return Auth::user()?->role === 'resident'
                ? route('resident.requests.show', $this->related_id)
                : route('admin.requests.show', $this->related_id);
        }

        if ($this->related_type === Concern::class) {
            return Auth::user()?->role === 'resident'
                ? route('resident.concerns.show', $this->related_id)
                : route('admin.concerns.show', $this->related_id);
        }

        if ($this->related_type === Announcement::class) {
            return Auth::user()?->role === 'resident'
                ? route('resident.announcements.show', $this->related_id)
                : route('admin.announcements.edit', $this->related_id);
        }

        return null;
    }
}
