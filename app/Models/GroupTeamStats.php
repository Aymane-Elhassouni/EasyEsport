<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTeamStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'team_id',
        'played',
        'wins',
        'draws',
        'losses',
        'points',
        'score_diff',
    ];

    public function group()
    {
        return $this->belongsTo(TournamentGroup::class, 'group_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
