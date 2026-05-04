<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'name',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function teamStats()
    {
        return $this->hasMany(GroupTeamStats::class, 'group_id');
    }

    public function matches()
    {
        // Matches can be linked to a group via a column we might add, 
        // or we can query matches where teams belong to this group.
        // For now, let's assume matches table might need a group_id too for ease of querying.
    }
}
