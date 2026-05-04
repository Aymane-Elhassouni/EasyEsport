<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
        'slug',
        'format',
        'status',
        'start_date',
        'end_date',
        'max_teams',
        'players_per_team',
        'created_by',
        'has_group_stage',
        'teams_per_group',
        'qualifiers_per_group',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(fn($t) => $t->slug = Str::slug($t->name));
        static::updating(function ($t) {
            if ($t->isDirty('name')) {
                $t->slug = Str::slug($t->name);
            }
        });
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'has_group_stage' => 'boolean',
    ];

    public function groups()
    {
        return $this->hasMany(TournamentGroup::class);
    }

    public const FORMAT_LABELS = [
        'single_elimination' => 'Single Elimination',
        'double_elimination' => 'Double Elimination',
        'league'             => 'League',
        'round_robin'        => 'Round Robin',
    ];

    public function getFormatLabelAttribute(): string
    {
        // Try to find the label in our map, otherwise format the raw string
        return self::FORMAT_LABELS[$this->format] 
            ?? str_replace('_', ' ', ucwords($this->format, '_'));
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(TournamentRegistration::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'tournament_registrations')
                    ->withPivot('status', 'registered_at')
                    ->withTimestamps();
    }

    public function announcements()
    {
        return $this->hasMany(TournamentAnnouncement::class)->latest();
    }

    public function brackets()
    {
        return $this->hasMany(Bracket::class);
    }
}
