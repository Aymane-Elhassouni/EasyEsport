<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'captain_id',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(fn($team) => $team->slug = Str::slug($team->name));
        static::updating(function ($team) {
            if ($team->isDirty('name')) {
                $team->slug = Str::slug($team->name);
            }
        });
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return config('filesystems.disks.s3.url') . '/' . $this->logo;
        }
        return 'https://api.dicebear.com/7.x/identicon/svg?seed=' . urlencode($this->name);
    }

    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function tournamentRegistrations()
    {
        return $this->hasMany(TournamentRegistration::class);
    }
}
