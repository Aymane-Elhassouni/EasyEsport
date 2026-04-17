<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'logo',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProfilePlayer::class);
    }

    public function playerGameProfiles()
    {
        return $this->hasMany(PlayerGameProfile::class);
    }

    public function captainOf()
    {
        return $this->hasMany(Team::class, 'captain_id');
    }

    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'invited_user_id');
    }

    public function createdTournaments()
    {
        return $this->hasMany(Tournament::class, 'created_by');
    }

    public function raisedDisputes()
    {
        return $this->hasMany(Dispute::class, 'raised_by');
    }

    public function resolvedDisputes()
    {
        return $this->hasMany(Dispute::class, 'resolved_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function currentTeam(): ?Team
    {
        $teamId = TeamMember::where('user_id', $this->id)->latest()->value('team_id')
            ?? Team::where('captain_id', $this->id)->latest()->value('id');
        return $teamId ? Team::with('members.user')->find($teamId) : null;
    }

    public function isCaptain(): bool
    {
        return Team::where('captain_id', $this->id)->exists();
    }
}
