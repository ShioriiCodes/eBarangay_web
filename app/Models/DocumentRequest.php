<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentRequest extends Model
{
    protected $fillable = [
        'request_number',
        'user_id',
        'document_type',
        'request_subtype',
        'purpose',
        'request_data',
        'status',
        'remarks',
        'reviewed_by',
        'reviewed_at',
        'approved_at',
        'completed_at',
        'printable_file_path',
    ];

    protected function casts(): array
    {
        return [
            'request_data' => 'array',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(DocumentStatusHistory::class);
    }
}
