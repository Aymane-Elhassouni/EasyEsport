<?php

namespace App\DTO;

use Carbon\Carbon;

final class AnnouncementViewData
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $slug,
        public readonly string  $title,
        public readonly string  $body,
        public readonly string  $tournamentName,
        public readonly string  $tournamentSlug,
        public readonly string  $gameName,
        public readonly ?string $gameLogo,
        public readonly ?string $bannerUrl,
        public readonly string  $authorName,
        public readonly string  $timeAgo,
        public readonly Carbon  $createdAt,
        public readonly int     $maxTeams,
        public readonly string  $format,
        public readonly int     $playersPerTeam,
    ) {}
}
