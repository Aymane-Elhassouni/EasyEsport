<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send a notification to a specific user.
     */
    public function send(int $userId, string $title, string $message, array $options = []): Notification
    {
        return Notification::create([
            'user_id'       => $userId,
            'creator_id'    => $options['creator_id'] ?? auth()->id(),
            'team_id'       => $options['team_id'] ?? null,
            'tournament_id' => $options['tournament_id'] ?? null,
            'title'         => $title,
            'message'       => $message,
            'action_url'    => $options['action_url'] ?? null,
            'icon'          => $options['icon'] ?? '🔔',
            'type'          => $options['type'] ?? 'info',
            'is_read'       => false,
        ]);
    }

    /**
     * Notify an entire team (or just the captain).
     */
    public function notifyTeam(int $teamId, string $title, string $message, bool $onlyCaptain = true, array $options = []): void
    {
        $team = \App\Models\Team::with('members')->find($teamId);
        if (!$team) return;

        $usersToNotify = $onlyCaptain ? [$team->captain_id] : $team->members->pluck('id')->toArray();

        foreach ($usersToNotify as $userId) {
            if ($userId) {
                $this->send($userId, $title, $message, array_merge($options, ['team_id' => $teamId]));
            }
        }
    }
}
