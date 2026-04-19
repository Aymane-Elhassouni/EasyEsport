<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilePlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'total_matches',
        'win_rate',
        'total_trophies',
        'status',
    ];

    protected $casts = [
        'total_matches'  => 'integer',
        'total_trophies' => 'integer',
        'win_rate'       => 'float',
        'status'         => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'FREE');
    }

    public function getFormattedWinRateAttribute(): string
    {
        return number_format($this->win_rate, 2) . '%';
    }
}
