<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'banner',
        'players_per_team',
    ];

    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }

    public function playerGameProfiles()
    {
        return $this->hasMany(PlayerGameProfile::class);
    }
}
