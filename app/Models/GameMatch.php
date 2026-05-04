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
        'tournament_id',
        'group_id',
        'team_a_id',
        'team_b_id',
        'next_match_id',
        'position',
        'winner_id',
        'score_a',
        'score_b',
        'status',
        'scheduled_at',
        'team_a_screenshot',
        'team_b_screenshot',
        'ocr_confidence',
        'played_at',
    ];

    public function group()
    {
        return $this->belongsTo(TournamentGroup::class, 'group_id');
    }

    protected $casts = [
        'played_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'ocr_confidence' => 'float',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

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

    public function nextMatch()
    {
        return $this->belongsTo(GameMatch::class, 'next_match_id');
    }

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner_id');
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
