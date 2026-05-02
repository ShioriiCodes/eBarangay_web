<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'password',
        'role',
        'contact_number',
        'address',
        'birthdate',
        'gender',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'birthdate' => 'date',
            'password' => 'hashed',
        ];
    }

    public function documentRequests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class);
    }

    public function concerns(): HasMany
    {
        return $this->hasMany(Concern::class);
    }

    public function notificationsList(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function residentProfile(): HasOne
    {
        return $this->hasOne(ResidentProfile::class);
    }

    public function reviewedDocumentRequests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class, 'reviewed_by');
    }

    public function handledConcerns(): HasMany
    {
        return $this->hasMany(Concern::class, 'handled_by');
    }

    public function changedDocumentStatuses(): HasMany
    {
        return $this->hasMany(DocumentStatusHistory::class, 'changed_by');
    }

    /**
     * First name for UI greetings (dashboard, etc.).
     */
    public function greetingFirstName(): string
    {
        if (filled($this->first_name)) {
            return $this->first_name;
        }

        $name = trim((string) $this->name);
        if ($name === '') {
            return 'there';
        }

        return explode(' ', $name, 2)[0];
    }
}
