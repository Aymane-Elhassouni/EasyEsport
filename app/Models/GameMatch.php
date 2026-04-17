<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'bracket_id',
        'team_a_id',
        'team_b_id',
        'score_a',
        'score_b',
        'status',
        'played_at',
    ];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function bracket()
    {
        return $this->belongsTo(Bracket::class);
    }

    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    public function ocrAnalysis()
    {
        return $this->hasOne(OcrAnalysis::class, 'match_id');
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class, 'match_id');
    }

    public function result(int $teamId): string
    {
        if ($this->status !== 'completed') return 'pending';
        if ($this->score_a === $this->score_b) return 'draw';
        $isTeamA = $this->team_a_id === $teamId;
        $won = $isTeamA ? $this->score_a > $this->score_b : $this->score_b > $this->score_a;
        return $won ? 'win' : 'loss';
    }

    public function opponent(int $teamId): ?Team
    {
        return $this->team_a_id === $teamId ? $this->teamB : $this->teamA;
    }

    public function timeRemaining(): string
    {
        return $this->played_at?->diffForHumans() ?? 'TBD';
    }
}
