<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Invitation;
use App\Models\User;
use App\Services\Interfaces\TeamServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TeamService implements TeamServiceInterface
{
    public function invitePlayer(int $teamId, string $email, int $captainId): void
    {
        $team = Team::findOrFail($teamId);
        $user = User::where('email', $email)->first();

        if ($user) {
            $inv = Invitation::firstOrCreate(
                ['team_id' => $teamId, 'invited_user_id' => $user->id, 'type' => 'invitation'],
                ['status' => 'pending', 'sent_at' => now()]
            );

            $notif = Notification::create([
                'user_id'    => $user->id,
                'creator_id' => $captainId,
                'team_id'    => $teamId,
                'title'      => 'Team Invitation',
                'message'    => "You have been invited to join {$team->name}.",
                'action_url' => route('invitations'),
                'icon'       => '🎮',
                'type'       => 'invitation',
            ]);


        } else {
            Mail::raw(
                "You've been invited to join {$team->name} on EasyEsport! Register at " . config('app.url') . '/register',
                fn($msg) => $msg->to($email)->subject("You're invited to join {$team->name}!")
            );
        }
    }

    public function requestJoin(int $teamId, int $userId): void
    {
        $team = Team::findOrFail($teamId);

        $alreadyPending = Invitation::where('team_id', $teamId)
            ->where('invited_user_id', $userId)
            ->where('type', 'join_request')
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) return;

        $inv = Invitation::create([
            'team_id'         => $teamId,
            'invited_user_id' => $userId,
            'type'            => 'join_request',
            'status'          => 'pending',
            'sent_at'         => now(),
        ]);

        $player = User::find($userId);

        $notif = Notification::create([
            'user_id'    => $team->captain_id,
            'creator_id' => $userId,
            'team_id'    => $teamId,
            'title'      => 'Join Request',
            'message'    => $player->firstname . ' wants to join ' . $team->name . '.',
            'action_url' => route('teams.manage', $team->slug) . '?request=' . $inv->id,
            'icon'       => '🙋',
            'type'       => 'join_request',
        ]);


    }

    public function createTeam(int $userId, array $data): Team
    {
        return DB::transaction(function () use ($userId, $data) {
            $team = Team::create([
                'name'       => $data['name'],
                'logo'       => $data['logo'] ?? null,
                'captain_id' => $userId,
            ]);

            $captainRole = Role::where('name', 'captain')->firstOrFail();
            User::where('id', $userId)->update(['role_id' => $captainRole->id]);

            return $team;
        });
    }

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
            'invitations' => fn($q) => $q->where('status', 'pending'),
            'invitations.invitedUser.profile',
            'invitations.invitedUser.playerGameProfiles',
        ])->findOrFail($teamId);
    }

    public function updateTeam(int $teamId, array $data): bool
    {
        return Team::findOrFail($teamId)->update($data);
    }

    public function kickMember(int $teamId, int $userId): bool
    {
        $deleted = TeamMember::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->delete() > 0;


        return $deleted;
    }

    public function handleInvitation(int $invitationId, string $status): bool
    {
        $invitation = Invitation::findOrFail($invitationId);

        if ($status === 'accepted') {
            DB::transaction(function () use ($invitation) {
                TeamMember::create([
                    'team_id'   => $invitation->team_id,
                    'user_id'   => $invitation->invited_user_id,
                    'joined_at' => now(),
                ]);
                $invitation->update(['status' => 'accepted']);
            });
        } else {
            $invitation->update(['status' => 'rejected']);
        }

        return true;
    }
}
