<?php

namespace App\Presenters;

use App\DTO\AnnouncementViewData;
use App\Models\TournamentAnnouncement;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AnnouncementPresenter
{
    public function present(TournamentAnnouncement $ann): AnnouncementViewData
    {
        return new AnnouncementViewData(
            id:             $ann->id,
            slug:           $ann->slug ?? Str::slug($ann->title) . '-' . $ann->id,
            title:          $ann->title,
            body:           $ann->body,
            tournamentName: $ann->tournament->name,
            tournamentSlug: $ann->tournament->slug,
            gameName:       $ann->tournament->game?->name ?? '—',
            gameLogo:       $ann->tournament->game?->logo,
            bannerUrl:      $ann->banner_url,
            authorName:     trim(($ann->author?->firstname ?? '') . ' ' . ($ann->author?->lastname ?? '')),
            timeAgo:        $ann->created_at->diffForHumans(),
            createdAt:      $ann->created_at,
            maxTeams:       $ann->tournament->max_teams,
            format:         $ann->tournament->format_label,
            playersPerTeam: $ann->tournament->players_per_team,
        );
    }

    public function presentCollection(Collection $announcements): Collection
    {
        return $announcements->map(fn($ann) => $this->present($ann));
    }
}
