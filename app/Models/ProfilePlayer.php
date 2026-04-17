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

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
