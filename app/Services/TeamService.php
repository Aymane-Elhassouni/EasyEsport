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

use App\DAOs\TeamDAO;

class TeamService implements TeamServiceInterface
{
    public function __construct(
        protected TeamDAO $teamDao
    ) {}

    public function getPaginatedTeams(?string $query = null, int $perPage = 12)
    {
        $paginator = $this->teamDao->getPaginatedTeams($query, $perPage);
        
        $paginator->getCollection()->transform(function ($team) {
            return \App\DTOs\TeamDTO::fromModel($team);
        });

        return $paginator;
    }

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

    public function getCurrentTeamForUser(?int $userId): ?\App\DTOs\TeamDTO
    {
        if (!$userId) return null;

        $team = $this->teamDao->findByUserId($userId);

        return $team ? \App\DTOs\TeamDTO::fromModel($team) : null;
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

    public function transferCaptaincy(int $teamId, int $newCaptainId, int $currentCaptainId): void
    {
        $team = Team::findOrFail($teamId);
        $newCaptain = User::findOrFail($newCaptainId);

        Invitation::updateOrCreate(
            ['team_id' => $teamId, 'invited_user_id' => $newCaptainId, 'type' => 'captain_transfer'],
            ['status' => 'pending', 'sent_at' => now()]
        );

        Notification::create([
            'user_id'    => $newCaptainId,
            'creator_id' => $currentCaptainId,
            'team_id'    => $teamId,
            'title'      => 'Captain Transfer',
            'message'    => "You have been offered the captaincy of {$team->name}.",
            'action_url' => route('invitations'),
            'icon'       => '👑',
            'type'       => 'captain_transfer',
        ]);
    }

    public function leaveTeam(int $teamId, int $userId): void
    {
        TeamMember::where('team_id', $teamId)->where('user_id', $userId)->delete();
    }

    public function handleInvitation(int $invitationId, string $status): bool
    {
        $invitation = Invitation::findOrFail($invitationId);

        if ($status === 'accepted') {
            DB::transaction(function () use ($invitation) {
                // Ensure user is removed from all other memberships before joining new team
                TeamMember::where('user_id', $invitation->invited_user_id)->delete();

                if ($invitation->type === 'captain_transfer') {
                    $team = Team::findOrFail($invitation->team_id);
                    $oldCaptainId = $team->captain_id;

                    // If user was already a captain of DIFFERENT team, they must vacate that first
                    $existingCaptaincy = Team::where('captain_id', $invitation->invited_user_id)
                        ->where('id', '!=', $team->id)
                        ->first();
                    
                    if ($existingCaptaincy) {
                        throw new \Exception("You are already a captain of team {$existingCaptaincy->name}. You must transfer your captaincy first.");
                    }

                    $team->update(['captain_id' => $invitation->invited_user_id]);

                    $captainRole = \App\Models\Role::where('name', 'captain')->firstOrFail();
                    $playerRole  = \App\Models\Role::where('name', 'player')->firstOrFail();

                    User::where('id', $invitation->invited_user_id)->update(['role_id' => $captainRole->id]);
                    User::where('id', $oldCaptainId)->update(['role_id' => $playerRole->id]);

                    TeamMember::updateOrCreate(
                        ['team_id' => $team->id, 'user_id' => $oldCaptainId],
                        ['joined_at' => now()]
                    );
                } else {
                    // Check if they are already a captain elsewhere before joining as a member
                    if (Team::where('captain_id', $invitation->invited_user_id)->exists()) {
                        throw new \Exception("You are a captain of another team. You must transfer your captaincy before joining another team as a member.");
                    }

                    TeamMember::create([
                        'team_id'   => $invitation->team_id,
                        'user_id'   => $invitation->invited_user_id,
                        'joined_at' => now(),
                    ]);
                }
                $invitation->update(['status' => 'accepted']);
            });
        } else {
            $invitation->update(['status' => 'rejected']);
        }

        return true;
    }
}
