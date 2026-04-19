<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerGameProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ign',
        'rank',
        'game_stats',
    ];

    protected $casts = [
        'game_stats' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
