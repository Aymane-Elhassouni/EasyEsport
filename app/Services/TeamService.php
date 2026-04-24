<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Invitation;
use App\Services\Interfaces\TeamServiceInterface;
use Illuminate\Support\Facades\DB;

class TeamService implements TeamServiceInterface
{
    public function getCurrentTeamForUser(int $userId): ?Team
    {
        return Team::with(['members'])
            ->where(function ($q) use ($userId) {
                $q->where('captain_id', $userId)
                  ->orWhereHas('members', fn($q) => $q->where('user_id', $userId));
            })
            ->withCount('members')
            ->latest('id')
            ->first();
    }

    public function getManageableTeam(int $teamId): Team
    {
        return Team::with([
            'captain',
            'members.user.profile',
            'members.user.playerGameProfiles',
            'invitations.invitedUser.profile' => fn($q) => $q->where('status', 'pending')
        ])->findOrFail($teamId);
    }

    public function updateTeam(int $teamId, array $data): bool
    {
        $team = Team::findOrFail($teamId);
        return $team->update($data);
    }

    public function kickMember(int $teamId, int $userId): bool
    {
        return TeamMember::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    public function handleInvitation(int $invitationId, string $status): bool
    {
        $invitation = Invitation::findOrFail($invitationId);
        
        if ($status === 'accepted') {
            DB::transaction(function () use ($invitation) {
                TeamMember::create([
                    'team_id' => $invitation->team_id,
                    'user_id' => $invitation->invited_user_id,
                    'joined_at' => now(),
                ]);
                $invitation->update(['status' => 'accepted']);
            });
            return true;
        }

        return $invitation->update(['status' => 'declined']);
    }
}
