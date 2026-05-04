<?php

namespace App\DAOs;

use App\Models\Tournament;
use Illuminate\Support\Collection;

class TournamentDAO
{
    public function getAllActive(): Collection
    {
        return Tournament::with(['game'])
            ->withCount(['registrations' => fn($q) => $q->where('status', 'approved')])
            ->latest()
            ->get();
    }

    public function findBySlug(string $slug): Tournament
    {
        return Tournament::with(['game', 'registrations.team', 'announcements.author', 'brackets.matches.teamA', 'brackets.matches.teamB', 'groups.teamStats.team'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function create(array $data): Tournament
    {
        return Tournament::create($data);
    }

    public function findById(int $id): Tournament
    {
        return Tournament::findOrFail($id);
    }
}
